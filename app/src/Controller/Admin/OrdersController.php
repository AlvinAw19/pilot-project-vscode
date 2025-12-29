<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Orders Controller for Admin
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\OrderItemsTable $OrderItems
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
        $this->loadModel('OrderItems');
    }

    /**
     * Index method - Display all orders
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        /** @var \App\Model\Entity\Order $order */
        $order = $this->Orders->newEmptyEntity();
        $this->Authorization->authorize($order, 'index');

        $orders = $this->paginate($this->Orders->find()
            ->contain(['Buyers', 'OrderItems', 'Payments'])
            ->order(['Orders.created' => 'DESC']));

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
            'contain' => [
                'Buyers',
                'OrderItems' => ['Products' => ['Users']],
                'Payments',
            ],
        ]);

        $this->Authorization->authorize($order, 'view');

        $this->set(compact('order'));
    }
}
