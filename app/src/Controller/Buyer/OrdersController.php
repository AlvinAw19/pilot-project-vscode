<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;
use App\Mailer\OrderMailer;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\OrderItemsTable $OrderItems
 * @property \App\Model\Table\PaymentsTable $Payments
 * @property \App\Model\Table\CartItemsTable $CartItems
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
        $this->loadModel('CartItems');
        $this->loadModel('OrderItems');
        $this->loadModel('Payments');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $order = $this->Orders->newEmptyEntity();
        $this->Authorization->authorize($order);

        $query = $this->Orders->find()
            ->where(['buyer_id' => $this->request->getAttribute('identity')->id])
            ->contain(['OrderItems', 'Payments'])
            ->order(['Orders.created' => 'DESC']);

        $orders = $this->paginate($query);

        $this->set(compact('orders'));
    }

    /**
     * View method
     *
     * @param string|null $id Order id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->get($id, [
            'contain' => ['OrderItems' => ['Products' => ['Users']], 'Payments'],
        ]);
        $this->Authorization->authorize($order);

        $this->set(compact('order'));
    }

    /**
     * Checkout method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function checkout()
    {
        $order = $this->Orders->newEmptyEntity();
        $this->Authorization->authorize($order);
        $buyerId = $this->request->getAttribute('identity')->id;

        // Get cart items for the current buyer
        $cartItems = $this->CartItems->find()
            ->where(['buyer_id' => $buyerId])
            ->contain(['Products'])
            ->all();

        if ($cartItems->isEmpty()) {
            $this->Flash->error(__('Your cart is empty.'));

            return $this->redirect(['controller' => 'Catalogs', 'action' => 'index']);
        }

        // Calculate total amount and validate stock availability
        $totalAmount = 0;
        $unavailableItems = [];

        foreach ($cartItems as $item) {
            // Check if product still exists and has stock
            if (!$item->product || $item->product->stock < $item->quantity) {
                $unavailableItems[] = $item->product ? $item->product->name : __('Unknown product');
            } else {
                $totalAmount += $item->product->price * $item->quantity;
            }
        }

        // Remove unavailable items from cart
        if (!empty($unavailableItems)) {
            $itemsToRemove = [];
            foreach ($cartItems as $item) {
                $productName = $item->product ? $item->product->name : __('Unknown product');
                if (in_array($productName, $unavailableItems)) {
                    $itemsToRemove[] = $item;
                }
            }
            $this->CartItems->deleteMany($itemsToRemove);

            $this->Flash->error(__(
                'Some items in your cart are no longer available: {0}',
                implode(', ', $unavailableItems)
            ));

            return $this->redirect(['controller' => 'CartItems', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Start transaction
            $this->Orders->getConnection()->begin();

            try {
                // Create order
                $order = $this->Orders->newEntity([
                    'buyer_id' => $buyerId,
                    'total_amount' => $totalAmount,
                ]);

                $order = $this->Orders->saveOrFail($order);

                // Create order items
                $orderItems = [];
                foreach ($cartItems as $cartItem) {
                    $orderItems[] = $this->OrderItems->newEntity([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'price' => $cartItem->product->price,
                        'quantity' => $cartItem->quantity,
                        'amount' => $cartItem->product->price * $cartItem->quantity,
                    ]);

                    $product = $cartItem->product;
                    $product->stock = $product->stock - $cartItem->quantity;
                    $this->OrderItems->Products->saveOrFail($product);
                }
                $this->OrderItems->saveManyOrFail($orderItems);

                // Create payment
                $payment = $this->Payments->newEntity([
                    'order_id' => $order->id,
                    'payment_type' => $data['payment_type'],
                ]);
                $this->Payments->saveOrFail($payment);

                // Clear cart
                $this->CartItems->deleteManyOrFail($cartItems);

                // Commit transaction
                $this->Orders->getConnection()->commit();

                // Reload order with associations for emails
                $order = $this->Orders->get($order->id, [
                    'contain' => ['Users', 'OrderItems' => ['Products' => ['Users']]],
                ]);

                // Send order confirmation to buyer
                $mailer = new OrderMailer();
                $mailer->orderConfirmation($order);

                // Send notification to each seller
                foreach ($order->order_items as $item) {
                    $mailer->sellerNotification($item);
                }

                $this->Flash->success(__('Order completed successfully.'));

                return $this->redirect(['action' => 'view', $order->id]);
            } catch (\Exception $e) {
                $this->Orders->getConnection()->rollback();
                $this->Flash->error(__('Order could not be completed. Please try again.'));
            }
        }

        $this->set(compact('cartItems', 'totalAmount'));
    }
}
