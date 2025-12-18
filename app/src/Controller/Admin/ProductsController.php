<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\User;
use Authorization\Exception\ForbiddenException;

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
     * Before filter callback.
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event The beforeFilter event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->request->getAttribute('identity');
        if (!$identity || $identity->get('role') !== User::ROLE_ADMIN) {
            throw new ForbiddenException(null, __('Access denied'));
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

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
        $product = $this->Products
            ->find()
            ->where(['Products.slug' => $slug])
            ->contain(['Categories', 'Users'])
            ->firstOrFail();
        $this->Authorization->authorize($product);

        $this->set(compact('product'));
    }
}
