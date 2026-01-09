<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;

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

        $itemIds = $this->request->getData('item_ids');
        $newStatus = $this->request->getData('delivery_status');

        if (empty($itemIds) || empty($newStatus)) {
            $this->Flash->error(__('Please select items and status to update.'));

            return $this->redirect(['action' => 'index']);
        }

        // Get order items
        $orderItems = $this->OrderItems
            ->find()
            ->where(['OrderItems.id IN' => $itemIds])
            ->contain(['Products'])
            ->all();

        $updatedCount = 0;
        $orderIdsToNotify = [];

        foreach ($orderItems as $orderItem) {
            $this->Authorization->authorize($orderItem);
            $orderItem->delivery_status = $newStatus;
            if ($this->OrderItems->save($orderItem)) {
                $updatedCount++;
                // Track unique order IDs for notification
                if (!in_array($orderItem->order_id, $orderIdsToNotify)) {
                    $orderIdsToNotify[] = $orderItem->order_id;
                }
            }
        }

        if ($updatedCount > 0) {
            $this->Flash->success(__('Updated {0} item(s) to {1}.', $updatedCount, $newStatus));

            // Send email notifications to buyers for each affected order
            $ordersTable = $this->fetchTable('Orders');
            foreach ($orderIdsToNotify as $orderId) {
                try {
                    /** @var \App\Model\Entity\Order $order */
                    $order = $ordersTable->get($orderId, [
                        'contain' => ['OrderItems' => ['Products'], 'Users'],
                    ]);

                    // Send status update email to buyer
                    if (isset($order->user)) {
                        (new \App\Mailer\OrderMailer())
                            ->send('orderStatusUpdated', [$order, $order->user]);
                    }
                } catch (\Exception $e) {
                    // Log error but don't stop the update process
                    \Cake\Log\Log::error('Failed to send order status update email: ' . $e->getMessage());
                }
            }
        }

        return $this->redirect(['action' => 'index']);
    }
}
