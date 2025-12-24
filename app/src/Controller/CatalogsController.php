<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Catalogs Controller
 *
 * Public-facing product catalog for buyers
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class CatalogsController extends AppController
{
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
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('Categories');
        
        // Skip authorization for all actions in this controller
        $this->Authorization->skipAuthorization();
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
        $query = $this->Products->find('search', [
            'search' => $this->request->getQueryParams(),
        ])
            ->where([
                'Products.deleted IS' => null,
                'Products.stock >' => 0,
            ])
            ->contain(['Categories'])
            ->order(['Products.created' => 'DESC']);

        $products = $this->paginate($query, [
            'limit' => 12,
        ]);

        // Get all categories for filter
        $categories = $this->Categories->find('all')
            ->where(['Categories.deleted IS' => null])
            ->order(['Categories.name' => 'ASC'])
            ->all();

        $searchTerm = $this->request->getQuery('q');
        $categoryId = $this->request->getQuery('category_id');
        
        // Get selected category if filtering
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->Categories->get($categoryId);
        }

        $this->set(compact('products', 'categories', 'searchTerm', 'categoryId', 'selectedCategory'));
    }

    /**
     * Display product details by slug
     *
     * Shows detailed product information including name, description,
     * price, category, image, and stock. Only non-deleted products.
     *
     * @param string|null $slug Product slug
     * @return void
     */
    public function view($slug = null)
    {
        $product = $this->Products->find()
            ->where([
                'Products.slug' => $slug,
                'Products.deleted IS' => null,
            ])
            ->contain(['Categories', 'Users'])
            ->firstOrFail();

        $this->set(compact('product'));
    }
}
