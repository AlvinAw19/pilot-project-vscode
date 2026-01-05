<?php
declare(strict_types=1);

namespace App\Controller\Admin;

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
        $this->Authorization->skipAuthorization();

        $orders = $this->paginate(
            $this->Orders
                ->find()
                ->contain(['Users', 'OrderItems', 'Payments'])
        );

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

        $order = $this->Orders->get($id, [
            'contain' => [
                'Users', 
                'Payments', 
                'OrderItems' => ['Products' => ['Users']]  
            ],
        ]);

        $this->set(compact('order'));
    }
}
