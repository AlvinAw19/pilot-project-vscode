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
            'contain' => [
                'OrderItems' => [
                    'Products' => [
                        'Users',
                    ],
                    'Reviews',
                ],
                'Payments',
            ],
        ]);
        $this->Authorization->authorize($order);

        $this->set(compact('order'));
    }
}
