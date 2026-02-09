<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;
use App\Service\MinioService;

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

        $query = $this->Products->find()
            ->where(['seller_id' => $this->request->getAttribute('identity')->id])
            ->contain(['Categories']);
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

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        /** @var \App\Model\Entity\Product $product */
        $product = $this->Products->newEmptyEntity();
        $this->Authorization->authorize($product);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $minioService = new MinioService();

            if (!empty($data['image_link'])) {
                $uploadedUrl = $minioService->uploadImage(
                    $data['image_link'],
                    'products'
                );
                if ($uploadedUrl) {
                    $data['image_link'] = $uploadedUrl;
                } else {
                    unset($data['image_link']);
                }
            }

            $product = $this->Products->patchEntity($product, $data);
            $product->seller_id = $this->request->getAttribute('identity')->id;
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $categories = $this->Products->Categories->find('list')->all();
        $this->set(compact('product', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string $slug Product slug.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($slug)
    {
        /** @var \App\Model\Entity\Product $product */
        $product = $this->Products
            ->find()
            ->where(['Products.slug' => $slug])
            ->firstOrFail();
        $this->Authorization->authorize($product);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $minioService = new MinioService();

            if (!empty($data['image_link'])) {
                $uploadedUrl = $minioService->uploadImage(
                    $data['image_link'],
                    'products'
                );
                if ($uploadedUrl) {
                    $data['image_link'] = $uploadedUrl;
                } else {
                    unset($data['image_link']);
                }
            }

            $product = $this->Products->patchEntity($product, $data);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $categories = $this->Products->Categories->find('list')->all();
        $this->set(compact('product', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string $slug Product slug.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        /** @var \App\Model\Entity\Product $product */
        $product = $this->Products
            ->find()
            ->where(['Products.slug' => $slug])
            ->firstOrFail();
        $this->Authorization->authorize($product);

        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
