<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;
use App\Enum\DeliveryStatus;

/**
 * Orders Controller
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
        $this->loadModel('OrderItems');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orderItem = $this->OrderItems->newEmptyEntity();
        $this->Authorization->authorize($orderItem);
        $sellerId = $this->request->getAttribute('identity')->id;

        $query = $this->OrderItems->find()
            ->contain(['Orders' => ['Users', 'Payments'], 'Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->order(['OrderItems.created' => 'DESC']);

        $orderItems = $this->paginate($query);

        $this->set(compact('orderItems'));
        $this->set('deliveryStatusOptions', DeliveryStatus::options());
    }

    /**
     * Update status method - Bulk update delivery status
     *
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function updateStatus()
    {
        $this->request->allowMethod(['post']);
        $this->Authorization->skipAuthorization();

        $orderItemIds = $this->request->getData('order_item_ids');
        $newStatus = $this->request->getData('delivery_status');

        if (empty($orderItemIds) || empty($newStatus)) {
            $this->Flash->error(__('Please select items and status to update.'));

            return $this->redirect(['action' => 'index']);
        }

        // Get order items
        $orderItems = $this->OrderItems
            ->find()
            ->where(['OrderItems.id IN' => $orderItemIds])
            ->contain(['Products'])
            ->all();

        $updatedCount = 0;

        foreach ($orderItems as $orderItem) {
            $this->Authorization->authorize($orderItem);
            $orderItem->delivery_status = $newStatus;
            if ($this->OrderItems->save($orderItem)) {
                $updatedCount++;
            }
        }

        if ($updatedCount > 0) {
            $this->Flash->success(__('Updated {0} item(s) to {1}.', $updatedCount, $newStatus));
        }

        return $this->redirect(['action' => 'index']);
    }
}
