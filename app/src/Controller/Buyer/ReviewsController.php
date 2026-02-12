<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;
use App\Service\MinioService;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 * @property \App\Model\Table\OrderItemsTable $OrderItems
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\Review[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewsController extends AppController
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
     * Add method
     *
     * @param int|null $orderItemId The order item ID to review.
     * @return \Cake\Http\Response|null|void Redirects on successful add.
     */
    public function add($orderItemId = null)
    {
        $userId = $this->request->getAttribute('identity')->id;

        // Load the order item with its order to verify ownership
        $orderItem = $this->OrderItems->get($orderItemId, [
            'contain' => ['Orders', 'Products'],
        ]);

        $review = $this->Reviews->newEmptyEntity();
        $review->user_id = $userId;
        $review->order_item = $orderItem;
        $this->Authorization->authorize($review);

        // Check if a review already exists for this order item
        $existingReview = $this->Reviews->find()
            ->where(['order_item_id' => $orderItemId])
            ->first();

        if ($existingReview) {
            $this->Flash->error(__('You have already reviewed this item.'));

            return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderItem->order_id]);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $minioService = new MinioService();

            // Handle image upload via MinIO (same pattern as ProductsController)
            if (!empty($data['image_link'])) {
                $uploadedUrl = $minioService->uploadImage(
                    $data['image_link'],
                    'reviews'
                );
                if ($uploadedUrl) {
                    $data['image_link'] = $uploadedUrl;
                } else {
                    unset($data['image_link']);
                }
            }

            $data['user_id'] = $userId;
            $data['product_id'] = $orderItem->product_id;
            $data['order_item_id'] = (int)$orderItemId;

            $review = $this->Reviews->patchEntity($review, $data);

            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('Your review has been submitted.'));

                return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderItem->order_id]);
            }
            $this->Flash->error(__('Your review could not be saved. Please try again.'));
        }

        $this->set(compact('review', 'orderItem'));
    }

    /**
     * Edit method
     *
     * @param string|null $orderItemId Review id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($orderItemId = null)
    {
        $userId = $this->request->getAttribute('identity')->id;

        // Load the order item with its order to verify ownership
        $orderItem = $this->OrderItems->get($orderItemId, [
            'contain' => ['Orders', 'Products'],
        ]);

        /** @var \App\Model\Entity\Review $review */
        $review = $this->Reviews->find()
            ->where([
                'order_item_id' => $orderItemId,
                'user_id' => $userId,
            ])
            ->firstOrFail();
        $this->Authorization->authorize($review);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $minioService = new MinioService();

            // Handle image upload via MinIO (same pattern as ProductsController)
            if (!empty($data['image_link'])) {
                $uploadedUrl = $minioService->uploadImage(
                    $data['image_link'],
                    'reviews'
                );
                if ($uploadedUrl) {
                    $data['image_link'] = $uploadedUrl;
                } else {
                    unset($data['image_link']);
                }
            }

            $review = $this->Reviews->patchEntity($review, $data);

            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('Your review has been updated.'));

                return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderItem->order_id]);
            }
            $this->Flash->error(__('Your review could not be saved. Please try again.'));
        }

        $this->set(compact('review', 'orderItem'));
    }
}
