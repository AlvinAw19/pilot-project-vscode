<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;
use App\Model\Entity\OrderItem;

/**
 * Orders Controller for Sellers
 *
 * @property \App\Model\Table\OrderItemsTable $OrderItems
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\OrderItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
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
        $this->loadModel('Products');
    }

    /**
     * Index method - Display order items for seller's products
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        /** @var \App\Model\Entity\OrderItem $orderItem */
        $orderItem = $this->OrderItems->newEmptyEntity();
        $this->Authorization->authorize($orderItem, 'index');

        $sellerId = $this->request->getAttribute('identity')->id;

        $query = $this->OrderItems->find('bySeller', ['seller_id' => $sellerId]);

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

        $sellerId = $this->request->getAttribute('identity')->id;
        $itemIds = $this->request->getData('item_ids');
        $newStatus = $this->request->getData('delivery_status');

        if (empty($itemIds) || empty($newStatus)) {
            $this->Flash->error(__('Please select items and status to update.'));
            return $this->redirect(['action' => 'index']);
        }

        // Validate status
        $validStatuses = [
            OrderItem::STATUS_PENDING,
            OrderItem::STATUS_DELIVERING,
            OrderItem::STATUS_DELIVERED,
            OrderItem::STATUS_CANCELED,
        ];

        if (!in_array($newStatus, $validStatuses)) {
            $this->Flash->error(__('Invalid delivery status.'));
            return $this->redirect(['action' => 'index']);
        }

        // Get order items
        $orderItems = $this->OrderItems
            ->find()
            ->where(['OrderItems.id IN' => $itemIds])
            ->contain(['Products'])
            ->all();

        $updatedCount = 0;
        $errors = [];

        foreach ($orderItems as $orderItem) {
            // Authorize each item update
            try {
                $this->Authorization->authorize($orderItem, 'updateStatus');
            } catch (\Exception $e) {
                $errors[] = __('Not authorized to update item #{0}', $orderItem->id);
                continue;
            }

            // Verify item belongs to seller's product
            if ($orderItem->product->seller_id !== $sellerId) {
                $errors[] = __('Item #{0} does not belong to your products', $orderItem->id);
                continue;
            }

            $orderItem->delivery_status = $newStatus;
            if ($this->OrderItems->save($orderItem)) {
                $updatedCount++;
            } else {
                $errors[] = __('Failed to update item #{0}', $orderItem->id);
            }
        }

        if ($updatedCount > 0) {
            $this->Flash->success(__('Updated {0} item(s) to {1}.', $updatedCount, $newStatus));
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->Flash->error($error);
            }
        }

        return $this->redirect(['action' => 'index']);
    }
}
