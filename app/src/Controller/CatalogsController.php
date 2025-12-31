<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Catalog Controller
 *
 * Public facing catalog pages.
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @property \App\Model\Table\ProductsTable $Products
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class CatalogsController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('Categories');

        $this->Authorization->skipAuthorization();
    }

    /**
     * beforeFilter callback.
     *
     * Allows all actions to be accessed without authentication
     * since this is a public-facing catalog.
     *
     * @param \Cake\Event\EventInterface<\App\Controller\CatalogsController> $event The event object.
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    /**
     * Display all available products with pagination
     *
     * Shows only products where deleted IS NULL and stock > 0,
     * includes associated categories. Handles search and filtering.
     *
     * @return void
     */
    public function index()
    {
        // Get query parameters
        $searchTerm = $this->request->getQuery('search');
        $categoryId = $this->request->getQuery('category_id');

        // Get all products
        $query = $this->Products->find('search', [
            'search' => $this->request->getQueryParams(),
        ])
            ->where([
                'Products.deleted IS' => null,
                'Products.stock >' => 0,
            ])
            ->contain(['Categories'])
            ->order(['Products.created' => 'DESC']);

        $products = $this->paginate($query);

        // Get all categories
        $categories = $this->Categories->find()
            ->where(['Categories.deleted IS' => null])
            ->order(['Categories.name' => 'ASC'])
            ->all();

        // Get selected category if filtering
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $categories->firstMatch(['id' => $categoryId]);
        }

        $this->set(compact('products', 'categories', 'searchTerm', 'categoryId', 'selectedCategory'));
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
            ->where([
                'Products.slug' => $slug,
                'Products.deleted IS' => null,
                'Products.stock >' => 0,
            ])
            ->contain(['Categories', 'Users'])
            ->firstOrFail();

        $this->set(compact('product'));
    }
}
