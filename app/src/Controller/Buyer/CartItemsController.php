<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;

/**
 * CartItems Controller
 *
 * @property \App\Model\Table\CartItemsTable $CartItems
 * @property \App\Model\Table\ProductsTable $Products
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\CartItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CartItemsController extends AppController
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
        $this->loadModel('Products');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $cartItem = $this->CartItems->newEmptyEntity();
        $this->Authorization->authorize($cartItem);

        $buyerId = $this->request->getAttribute('identity')->id;

        $cartItems = $this->CartItems
            ->find('buyerCartItems', ['buyer_id' => $buyerId])
            ->all();

        // Calculate total price
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        $this->set(compact('cartItems', 'totalPrice'));
    }

    /**
     * Add method
     *
     * @param string|null $productId product id.
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($productId = null)
    {
        $cartItem = $this->CartItems->newEmptyEntity();
        $this->Authorization->authorize($cartItem);

        $buyerId = $this->request->getAttribute('identity')->id;
        $requestedQuantity = max(1, (int)$this->request->getData('quantity', 1));

        // Validate product exists
        /** @var \App\Model\Entity\Product|null $product */
        $product = $this->Products->find()->where(['id' => $productId])->first();
        if (!$product) {
            $this->Flash->error(__('Product not found.'));

            return $this->redirect($this->referer());
        }

        // Validate stock
        if ($product->stock <= 0) {
            $this->Flash->error(__('Product is out of stock.'));

            return $this->redirect($this->referer());
        }

        // Check if product already in cart
        /** @var \App\Model\Entity\CartItem|null $cartItem */
        $cartItem = $this->CartItems->find()
            ->where(['buyer_id' => $buyerId, 'product_id' => $productId])
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $newQuantity = $cartItem->quantity + $requestedQuantity;
            if (!$this->validateStock($product, $newQuantity)) {
                return $this->redirect($this->referer());
            }
            $cartItem->quantity = $newQuantity;
            $message = __('Product quantity updated in cart.');
        } else {
            // Create new cart item
            if (!$this->validateStock($product, $requestedQuantity)) {
                return $this->redirect($this->referer());
            }
            $cartItem = $this->CartItems->newEntity([
                'buyer_id' => $buyerId,
                'product_id' => $productId,
                'quantity' => $requestedQuantity,
            ]);
            $message = __('Product added to cart.');
        }

        if ($this->CartItems->save($cartItem)) {
            $this->Flash->success($message);
        } else {
            $this->Flash->error(__('Could not update cart. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Update method - Update cart item quantity
     *
     * @param int|null $id Cart item id.
     * @return \Cake\Http\Response|null Redirects on successful update.
     */
    public function update($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        $cartItem = $this->CartItems->get($id, ['contain' => ['Products']]);
        $this->Authorization->authorize($cartItem);

        $quantity = (int)$this->request->getData('quantity');

        if ($quantity <= 0) {
            $this->Flash->error(__('Quantity must be at least 1.'));

            return $this->redirect(['action' => 'index']);
        }

        if (!$this->validateStock($cartItem->product, $quantity)) {
            return $this->redirect(['action' => 'index']);
        }

        $cartItem->quantity = $quantity;

        if ($this->CartItems->save($cartItem)) {
            $this->Flash->success(__('Cart updated successfully.'));
        } else {
            $this->Flash->error(__('Could not update cart. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id cart item id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cartItem = $this->CartItems->get($id);
        $this->Authorization->authorize($cartItem);

        if ($this->CartItems->delete($cartItem)) {
            $this->Flash->success(__('The cart item has been deleted.'));
        } else {
            $this->Flash->error(__('The cart item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Validate if requested quantity is available in stock
     *
     * @param \App\Model\Entity\Product $product Product entity
     * @param int $quantity Requested quantity
     * @return bool True if valid, false otherwise
     */
    private function validateStock($product, int $quantity): bool
    {
        if ($quantity > $product->stock) {
            $this->Flash->error(__('Only {0} items available in stock.', $product->stock));

            return false;
        }

        return true;
    }
}
