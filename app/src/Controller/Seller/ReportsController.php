<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;

/**
 * Reports Controller
 *
 * Dashboard and reporting for Seller users.
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\OrderItemsTable $OrderItems
 * @property \App\Model\Table\ProductsTable $Products
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
    }

    /**
     * Index method - Seller Dashboard
     *
     * Displays sales performance, best-selling products, recent orders,
     * and low stock alerts for the current seller.
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

        $identity = $this->request->getAttribute('identity');
        $sellerId = $identity->getIdentifier();

        // Summary Cards Data
        $summaryCards = $this->getSummaryCards($sellerId);

        // Sales Over Time (Last 30 days)
        $salesOverTime = $this->getSalesOverTime($sellerId, 30);

        // Best Selling Products (Top 5)
        $bestSellingProducts = $this->getBestSellingProducts($sellerId, 5);

        // Recent Orders (Last 10)
        $recentOrders = $this->getRecentOrders($sellerId, 10);

        // Low Stock Alerts (Stock <= 10)
        $lowStockProducts = $this->getLowStockProducts($sellerId, 10);

        // Pending Orders Count
        $pendingOrdersCount = $this->getPendingOrdersCount($sellerId);

        $title = __('Seller Dashboard');
        $this->set(compact(
            'title',
            'summaryCards',
            'salesOverTime',
            'bestSellingProducts',
            'recentOrders',
            'lowStockProducts',
            'pendingOrdersCount'
        ));
    }

    /**
     * Get summary card data for seller
     *
     * @param int $sellerId Seller ID
     * @return array<string, mixed>
     */
    private function getSummaryCards(int $sellerId): array
    {
        // Total Products
        $totalProducts = $this->Products->find()
            ->where(['seller_id' => $sellerId])
            ->count();

        // Total Sales (sum of order items for this seller's products)
        $totalSalesResult = $this->OrderItems->find()
            ->contain(['Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->select(['total_sales' => $this->OrderItems->find()->func()->sum('OrderItems.amount')])
            ->first();
        $totalSales = $totalSalesResult ? (float)$totalSalesResult->get('total_sales') : 0;

        // Total Orders (distinct orders containing this seller's products)
        $totalOrders = $this->OrderItems->find()
            ->contain(['Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->select(['order_id'])
            ->distinct(['order_id'])
            ->count();

        // Items Sold
        $itemsSoldResult = $this->OrderItems->find()
            ->contain(['Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->select(['items_sold' => $this->OrderItems->find()->func()->sum('OrderItems.quantity')])
            ->first();
        $itemsSold = $itemsSoldResult ? (int)$itemsSoldResult->get('items_sold') : 0;

        return [
            'totalProducts' => $totalProducts,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'itemsSold' => $itemsSold,
        ];
    }

    /**
     * Get sales over time data for chart
     *
     * @param int $sellerId Seller ID
     * @param int $days Number of days to look back
     * @return array<string, mixed>
     */
    private function getSalesOverTime(int $sellerId, int $days): array
    {
        $startDate = FrozenTime::now()->subDays($days);

        $salesData = $this->OrderItems->find()
            ->contain(['Products', 'Orders'])
            ->where(['Products.seller_id' => $sellerId])
            ->select([
                'date' => 'DATE(Orders.created)',
                'daily_sales' => $this->OrderItems->find()->func()->sum('OrderItems.amount'),
            ])
            ->where(['Orders.created >=' => $startDate])
            ->group(['DATE(Orders.created)'])
            ->order(['DATE(Orders.created)' => 'ASC'])
            ->toArray();

        $labels = [];
        $data = [];

        foreach ($salesData as $row) {
            $labels[] = $row->get('date');
            $data[] = (float)$row->get('daily_sales');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get best selling products
     *
     * @param int $sellerId Seller ID
     * @param int $limit Number of products to return
     * @return array<mixed>
     */
    private function getBestSellingProducts(int $sellerId, int $limit): array
    {
        return $this->OrderItems->find()
            ->contain(['Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->select([
                'product_name' => 'Products.name',
                'total_quantity' => $this->OrderItems->find()->func()->sum('OrderItems.quantity'),
                'total_revenue' => $this->OrderItems->find()->func()->sum('OrderItems.amount'),
            ])
            ->group(['Products.id', 'Products.name'])
            ->order(['total_quantity' => 'DESC'])
            ->limit($limit)
            ->toArray();
    }

    /**
     * Get recent orders containing seller's products
     *
     * @param int $sellerId Seller ID
     * @param int $limit Number of orders to return
     * @return array<\App\Model\Entity\Order>
     */
    private function getRecentOrders(int $sellerId, int $limit): array
    {
        // Get distinct order IDs for this seller's products
        $orderIds = $this->OrderItems->find()
            ->contain(['Products'])
            ->where(['Products.seller_id' => $sellerId])
            ->select(['order_id'])
            ->distinct(['order_id'])
            ->enableAutoFields(false)
            ->extract('order_id')
            ->toArray();

        if (empty($orderIds)) {
            return [];
        }

        // Get orders with the most recent first
        return $this->Orders->find()
            ->contain(['Users', 'OrderItems' => function ($q) use ($sellerId) {
                return $q->innerJoinWith('Products', function ($q2) use ($sellerId) {
                    return $q2->where(['Products.seller_id' => $sellerId]);
                })->contain(['Products']);
            }])
            ->where(['Orders.id IN' => $orderIds])
            ->order(['Orders.created' => 'DESC'])
            ->limit($limit)
            ->toArray();
    }

    /**
     * Get low stock products
     *
     * @param int $sellerId Seller ID
     * @param int $threshold Stock threshold
     * @return array<\App\Model\Entity\Product>
     */
    private function getLowStockProducts(int $sellerId, int $threshold): array
    {
        return $this->Products->find()
            ->where([
                'seller_id' => $sellerId,
                'stock <=' => $threshold,
            ])
            ->order(['stock' => 'ASC'])
            ->toArray();
    }

    /**
     * Get count of pending order items for seller
     *
     * @param int $sellerId Seller ID
     * @return int
     */
    private function getPendingOrdersCount(int $sellerId): int
    {
        return $this->OrderItems->find()
            ->contain(['Products'])
            ->where([
                'Products.seller_id' => $sellerId,
                'OrderItems.delivery_status' => 'pending',
            ])
            ->count();
    }
}
