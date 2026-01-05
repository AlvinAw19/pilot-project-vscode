<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;

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
        $this->Authorization->skipAuthorization();

        $query = $this->Orders->find()
            ->where(['buyer_id' => $this->request->getAttribute('identity')->id])
            ->contain(['OrderItems', 'Payments']);

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
        $this->Authorization->skipAuthorization();

        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->get($id, [
            'contain' => ['OrderItems' => ['Products' => ['Users']], 'Payments']
        ]);

        $this->set(compact('order'));
    }

    /**
     * Checkout method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function checkout()
    {
        $this->Authorization->skipAuthorization();

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

        // Calculate total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
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
