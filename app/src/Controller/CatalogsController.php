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
        $this->loadModel('Reviews');

        // Skip authorization for all actions in this public controller
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

        // Get all products using custom finder
        $query = $this->Products
            ->find('search', ['search' => $this->request->getQueryParams()])
            ->find('activeProduct')
            ->contain(['Categories', 'Reviews'])
            ->order(['Products.created' => 'DESC']);

        $products = $this->paginate($query);

        // Get all categories using custom finder
        $categories = $this->Categories
            ->find('activeCategory')
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
            ->find('activeProduct')
            ->where(['Products.slug' => $slug])
            ->contain(['Categories', 'Users'])
            ->firstOrFail();

        // Load reviews for this product
        $reviews = $this->Reviews->find()
            ->where(['Reviews.product_id' => $product->id])
            ->contain(['Users'])
            ->order(['Reviews.created' => 'DESC'])
            ->all();

        // Calculate average rating
        $avgRating = null;
        $reviewCount = $reviews->count();
        if ($reviewCount > 0) {
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }
            $avgRating = round($totalRating / $reviewCount, 1);
        }

        $this->set(compact('product', 'reviews', 'avgRating', 'reviewCount'));
    }
}
