<?php
declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Controller\AppController;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;

/**
 * Cart Controller
 *
 * @property \App\Model\Table\CartItemsTable $CartItems
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\CartItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CartController extends AppController
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
     * Index method - Display buyer's cart
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        /** @var \App\Model\Entity\CartItem $cartItem */
        $cartItem = $this->CartItems->newEmptyEntity();
        $this->Authorization->authorize($cartItem, 'index');

        $buyerId = $this->request->getAttribute('identity')->id;

        $cartItems = $this->CartItems
            ->find()
            ->where(['CartItems.buyer_id' => $buyerId])
            ->contain(['Products'])
            ->all();

        // Calculate total price
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        $this->set(compact('cartItems', 'totalPrice'));
    }

    /**
     * Add method - Add product to cart or increment quantity
     *
     * @param int|null $productId Product id.
     * @return \Cake\Http\Response|null Redirects on successful add.
     */
    public function add($productId = null)
    {
        /** @var \App\Model\Entity\CartItem $cartItem */
        $cartItem = $this->CartItems->newEmptyEntity();
        $this->Authorization->authorize($cartItem, 'add');

        $buyerId = $this->request->getAttribute('identity')->id;

        // Validate product exists
        $product = $this->Products->find()
            ->where(['id' => $productId])
            ->first();

        if (!$product) {
            $this->Flash->error(__('Product not found.'));
            return $this->redirect($this->referer());
        }

        // Validate stock
        if ($product->stock <= 0) {
            $this->Flash->error(__('Product is out of stock.'));
            return $this->redirect($this->referer());
        }

        // Get quantity from request (default to 1 if not provided)
        $requestedQuantity = (int)$this->request->getData('quantity', 1);
        if ($requestedQuantity < 1) {
            $requestedQuantity = 1;
        }

        // Check if product already in cart
        $existingCartItem = $this->CartItems->find()
            ->where([
                'buyer_id' => $buyerId,
                'product_id' => $productId,
            ])
            ->first();

        if ($existingCartItem) {
            // Increment quantity by requested amount
            $newQuantity = $existingCartItem->quantity + $requestedQuantity;

            // Validate stock for new quantity
            if ($newQuantity > $product->stock) {
                $this->Flash->error(__('Cannot add more. Only {0} items available in stock.', $product->stock));
                return $this->redirect($this->referer());
            }

            $existingCartItem->quantity = $newQuantity;
            if ($this->CartItems->save($existingCartItem)) {
                $this->Flash->success(__('Product quantity updated in cart.'));
            } else {
                $this->Flash->error(__('Could not update cart. Please try again.'));
            }
        } else {
            // Validate requested quantity doesn't exceed stock
            if ($requestedQuantity > $product->stock) {
                $this->Flash->error(__('Cannot add {0} items. Only {1} available in stock.', $requestedQuantity, $product->stock));
                return $this->redirect($this->referer());
            }

            // Create new cart item with requested quantity
            $cartItem = $this->CartItems->newEntity([
                'buyer_id' => $buyerId,
                'product_id' => $productId,
                'quantity' => $requestedQuantity,
            ]);

            if ($this->CartItems->save($cartItem)) {
                $this->Flash->success(__('Product added to cart.'));
            } else {
                $this->Flash->error(__('Could not add product to cart. Please try again.'));
            }
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

        /** @var \App\Model\Entity\CartItem $cartItem */
        $cartItem = $this->CartItems->get($id, [
            'contain' => ['Products'],
        ]);

        $this->Authorization->authorize($cartItem, 'update');

        $quantity = (int)$this->request->getData('quantity');

        // Validate quantity
        if ($quantity <= 0) {
            $this->Flash->error(__('Quantity must be at least 1.'));
            return $this->redirect(['action' => 'index']);
        }

        // Validate stock
        if ($quantity > $cartItem->product->stock) {
            $this->Flash->error(__('Only {0} items available in stock.', $cartItem->product->stock));
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
     * Delete method - Remove item from cart
     *
     * @param int|null $id Cart item id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        /** @var \App\Model\Entity\CartItem $cartItem */
        $cartItem = $this->CartItems->get($id);
        $this->Authorization->authorize($cartItem, 'delete');

        if ($this->CartItems->delete($cartItem)) {
            $this->Flash->success(__('Item removed from cart.'));
        } else {
            $this->Flash->error(__('Could not remove item. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
