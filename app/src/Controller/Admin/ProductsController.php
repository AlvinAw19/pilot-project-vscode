<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        /** @var \App\Model\Entity\Product $product */
        $product = $this->Products->newEmptyEntity();
        $this->Authorization->authorize($product);

        $query = $this->Products->find()->contain(['Categories', 'Users']);
        $products = $this->paginate($query);

        $this->set(compact('products'));
    }

    /**
     * View method
     *
     * @param string $slug Product slug.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug)
    {
        /** @var \App\Model\Entity\Product $product */
        $product = $this->Products
            ->find()
            ->where(['Products.slug' => $slug])
            ->contain(['Categories', 'Users'])
            ->firstOrFail();
        $this->Authorization->authorize($product);

        $this->set(compact('product'));
    }
}
