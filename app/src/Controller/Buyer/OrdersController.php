<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Orders');
        $this->loadModel('CartItems');
        $this->loadModel('OrderItems');
        $this->loadModel('Payments');
    }

    /**
     * Index method - Display buyer's order history
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->newEmptyEntity();
        $this->Authorization->authorize($order, 'index');

        $buyerId = $this->request->getAttribute('identity')->id;

        $orders = $this->Orders
            ->find()
            ->where(['Orders.buyer_id' => $buyerId])
            ->contain(['OrderItems', 'Payments'])
            ->order(['Orders.created' => 'DESC'])
            ->all();

        $this->set(compact('orders'));
    }

    /**
     * View method - Display order details
     *
     * @param int|null $id Order id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->get($id, [
            'contain' => ['Buyers', 'OrderItems' => ['Products'], 'Payments'],
        ]);

        $this->Authorization->authorize($order, 'view');

        $this->set(compact('order'));
    }

    /**
     * Checkout method - Display checkout form or process order
     *
     * @return \Cake\Http\Response|null Redirects on successful checkout.
     */
    public function checkout()
    {
        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->newEmptyEntity();
        $this->Authorization->authorize($order, 'checkout');

        $buyerId = $this->request->getAttribute('identity')->id;

        // Get cart items
        $cartItems = $this->CartItems
            ->find()
            ->where(['CartItems.buyer_id' => $buyerId])
            ->contain(['Products'])
            ->all();

        if ($cartItems->count() === 0) {
            $this->Flash->error(__('Your cart is empty. Add products before checkout.'));
            return $this->redirect(['controller' => 'Cart', 'action' => 'index']);
        }

        // Validate stock availability for all items
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->stock) {
                $this->Flash->error(__(
                    'Insufficient stock for {0}. Only {1} available.',
                    $cartItem->product->name,
                    $cartItem->product->stock
                ));
                return $this->redirect(['controller' => 'Cart', 'action' => 'index']);
            }
        }

        // Calculate total
        $totalAmount = 0;
        foreach ($cartItems as $cartItem) {
            $totalAmount += $cartItem->product->price * $cartItem->quantity;
        }

        // Handle form submission
        if ($this->request->is('post')) {
            $paymentType = $this->request->getData('payment_type');

            if (empty($paymentType)) {
                $this->Flash->error(__('Please select a payment method.'));
                $this->set(compact('cartItems', 'totalAmount'));
                return;
            }

            // Use transaction to ensure data integrity
            $connection = $this->Orders->getConnection();
            $connection->begin();

            try {
                // Create order
                $order = $this->Orders->newEntity([
                    'buyer_id' => $buyerId,
                    'total_amount' => $totalAmount,
                ]);

                if (!$this->Orders->save($order)) {
                    throw new \Exception('Failed to create order');
                }

                // Create order items from cart items
                $orderItems = [];
                foreach ($cartItems as $cartItem) {
                    $orderItems[] = $this->OrderItems->newEntity([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'price' => $cartItem->product->price,
                        'quantity' => $cartItem->quantity,
                        'amount' => $cartItem->product->price * $cartItem->quantity,
                        'delivery_status' => 'pending',
                    ]);
                }

                if (!$this->OrderItems->saveMany($orderItems)) {
                    throw new \Exception('Failed to create order items');
                }

                // Create payment record with selected payment type
                $payment = $this->Payments->newEntity([
                    'order_id' => $order->id,
                    'payment_type' => $paymentType,
                ]);

                if (!$this->Payments->save($payment)) {
                    throw new \Exception('Failed to create payment record');
                }

                // Update product stock
                $ProductsTable = TableRegistry::getTableLocator()->get('Products');
                foreach ($cartItems as $cartItem) {
                    $product = $ProductsTable->get($cartItem->product_id);
                    $product->stock -= $cartItem->quantity;
                    if (!$ProductsTable->save($product)) {
                        throw new \Exception('Failed to update product stock');
                    }
                }

                // Clear cart only after successful order creation
                if (!$this->CartItems->deleteAll(['buyer_id' => $buyerId])) {
                    throw new \Exception('Failed to clear cart');
                }

                $connection->commit();

                $this->Flash->success(__('Order placed successfully! Order ID: {0}', $order->id));
                return $this->redirect(['action' => 'view', $order->id]);

            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('Checkout failed: {0}', $e->getMessage()));
                return $this->redirect(['controller' => 'Cart', 'action' => 'index']);
            }
        }

        // Display checkout form
        $this->set(compact('cartItems', 'totalAmount'));
    }
}
