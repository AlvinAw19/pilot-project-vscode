<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Http\Response;
use Cake\I18n\FrozenTime;

/**
 * Dashboard and reporting for Admin users.
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\OrderItemsTable $OrderItems
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\CategoriesTable $Categories
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class ReportsController extends AppController
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Orders');
        $this->loadModel('OrderItems');
        $this->loadModel('Products');
        $this->loadModel('Users');
        $this->loadModel('Categories');
    }

    /**
     * Displays platform-wide metrics, revenue trends, user growth,
     * top sellers, and system health indicators.
     *
     * @return \Cake\Http\Response Renders view
     */
    public function index(): Response
    {
        $identity = $this->request->getAttribute('identity');
        $user = $this->Users->get($identity->getIdentifier());
        $this->Authorization->authorize($user, 'AdminIndex');

        // Summary Cards Data
        $summaryCards = $this->getSummaryCards();

        // Revenue Over Time (Last 30 days)
        $revenueOverTime = $this->getRevenueOverTime(30);

        // Orders Over Time (Last 30 days)
        $ordersOverTime = $this->getOrdersOverTime(30);

        // User Growth (Last 30 days)
        $userGrowth = $this->getUserGrowth(30);

        // Top Sellers (Top 5)
        $topSellers = $this->getTopSellers(5);

        // Top Categories (Top 5)
        $topCategories = $this->getTopCategories(5);

        // System Health Indicators
        $systemHealth = $this->getSystemHealth();

        // Recent Orders (Last 10)
        $recentOrders = $this->getRecentOrders(10);

        $this->set(compact(
            'summaryCards',
            'revenueOverTime',
            'ordersOverTime',
            'userGrowth',
            'topSellers',
            'topCategories',
            'systemHealth',
            'recentOrders'
        ));

        return $this->render();
    }

    /**
     * Get summary card data for admin
     *
     * @return array<string, mixed>
     */
    private function getSummaryCards(): array
    {
        // Total Revenue
        /** @var \App\Model\Entity\Order $totalRevenue */
        $totalRevenue = $this->Orders->find()
            ->select(['total' => $this->Orders->find()->func()->sum('total_amount')])
            ->first();
        $totalRevenue = $totalRevenue->get('total');

        // Total Orders
        $totalOrders = $this->Orders->find()->count();

        // Total Users
        $totalUsers = $this->Users->find()->count();

        // Total Products
        $totalProducts = $this->Products->find()->count();

        // Total Sellers
        $totalSellers = $this->Users->find()
            ->where(['role' => User::ROLE_SELLER])
            ->count();

        // Total Buyers
        $totalBuyers = $this->Users->find()
            ->where(['role' => User::ROLE_BUYER])
            ->count();

        $totalAdmins = $this->Users->find()
            ->where(['role' => User::ROLE_ADMIN])
            ->count();

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalSellers' => $totalSellers,
            'totalBuyers' => $totalBuyers,
            'totalAdmins' => $totalAdmins,
        ];
    }

    /**
     * Get revenue over time data for chart
     *
     * @param int $days Number of days to look back
     * @return array<string, mixed>
     */
    private function getRevenueOverTime(int $days): array
    {
        $startDate = FrozenTime::now()->subDays($days);

        $revenueData = $this->Orders->find()
            ->select([
                'date' => 'DATE(created)',
                'daily_revenue' => $this->Orders->find()->func()->sum('total_amount'),
            ])
            ->where(['created >=' => $startDate])
            ->group(['DATE(created)'])
            ->order(['DATE(created)' => 'ASC'])
            ->toArray();

        // Initialize chart data
        $labels = [];
        $data = [];

        foreach ($revenueData as $row) {
            $labels[] = $row->get('date');
            $data[] = (float)$row->get('daily_revenue');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get orders over time data for chart
     *
     * @param int $days Number of days to look back
     * @return array<string, mixed>
     */
    private function getOrdersOverTime(int $days): array
    {
        $startDate = FrozenTime::now()->subDays($days);

        $ordersData = $this->Orders->find()
            ->select([
                'date' => 'DATE(created)',
                'order_count' => $this->Orders->find()->func()->count('id'),
            ])
            ->where(['created >=' => $startDate])
            ->group(['DATE(created)'])
            ->order(['DATE(created)' => 'ASC'])
            ->toArray();

        // Initialize chart data
        $labels = [];
        $data = [];

        foreach ($ordersData as $row) {
            $labels[] = $row->get('date');
            $data[] = (int)$row->get('order_count');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get user growth data for chart
     *
     * @param int $days Number of days to look back
     * @return array<string, mixed>
     */
    private function getUserGrowth(int $days): array
    {
        $startDate = FrozenTime::now()->subDays($days);

        $userData = $this->Users->find()
            ->select([
                'date' => 'DATE(created)',
                'user_count' => $this->Users->find()->func()->count('id'),
            ])
            ->where(['created >=' => $startDate])
            ->group(['DATE(created)'])
            ->order(['DATE(created)' => 'ASC'])
            ->toArray();

        // Initialize chart data
        $labels = [];
        $data = [];

        foreach ($userData as $row) {
            $labels[] = $row->get('date');
            $data[] = (int)$row->get('user_count');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get top sellers by revenue
     *
     * @param int $limit Number of sellers to return
     * @return array<mixed>
     */
    private function getTopSellers(int $limit): array
    {
        return $this->OrderItems->find()
            ->innerJoinWith('Products')
            ->innerJoinWith('Products.Users')
            ->select([
                'user_id' => 'Users.id',
                'user_name' => 'Users.name',
                'total_revenue' => $this->OrderItems->find()->func()->sum('OrderItems.amount'),
                'total_orders' => $this->OrderItems->find()->func()->count('DISTINCT OrderItems.order_id'),
            ])
            ->group(['Users.id'])
            ->order(['total_revenue' => 'DESC'])
            ->limit($limit)
            ->toArray();
    }

    /**
     * Get top categories by sales
     *
     * @param int $limit Number of categories to return
     * @return array<mixed>
     */
    private function getTopCategories(int $limit): array
    {
        return $this->OrderItems->find()
            ->innerJoinWith('Products')
            ->innerJoinWith('Products.Categories')
            ->select([
                'category_id' => 'Categories.id',
                'category_name' => 'Categories.name',
                'total_revenue' => $this->OrderItems->find()->func()->sum('OrderItems.amount'),
                'items_sold' => $this->OrderItems->find()->func()->sum('OrderItems.quantity'),
            ])
            ->group(['Categories.id'])
            ->order(['total_revenue' => 'DESC'])
            ->limit($limit)
            ->toArray();
    }

    /**
     * Get recent orders
     *
     * @param int $limit Number of orders to return
     * @return array<\App\Model\Entity\Order>
     */
    private function getRecentOrders(int $limit): array
    {
        return $this->Orders->find()
            ->contain(['Users', 'Payments'])
            ->order(['Orders.created' => 'DESC'])
            ->limit($limit)
            ->toArray();
    }

    /**
     * Get system health indicators
     *
     * @return array<string, mixed>
     */
    private function getSystemHealth(): array
    {
        // Pending Orders
        $pendingOrders = $this->OrderItems->find()
            ->where(['delivery_status' => 'pending'])
            ->select(['order_id'])
            ->distinct(['order_id'])
            ->count();

        // Inactive Sellers (sellers with no products)
        $sellersWithProducts = $this->Products->find()
            ->select(['seller_id'])
            ->distinct(['seller_id'])
            ->extract('seller_id')
            ->toArray();

        $inactiveSellers = $this->Users->find()
            ->where([
                'role' => User::ROLE_SELLER,
                'id NOT IN' => $sellersWithProducts,
            ])
            ->count();

        // Low Stock Products (stock <= 10)
        $lowStockProducts = $this->Products->find()
            ->where(['stock <=' => 10])
            ->count();

        // New Users Today
        $today = FrozenTime::now()->startOfDay();
        $newUsersToday = $this->Users->find()
            ->where(['created >=' => $today])
            ->count();

        // Orders Today
        $ordersToday = $this->Orders->find()
            ->where(['created >=' => $today])
            ->count();

        return [
            'pendingOrders' => $pendingOrders,
            'inactiveSellers' => $inactiveSellers,
            'lowStockProducts' => $lowStockProducts,
            'newUsersToday' => $newUsersToday,
            'ordersToday' => $ordersToday,
        ];
    }
}
