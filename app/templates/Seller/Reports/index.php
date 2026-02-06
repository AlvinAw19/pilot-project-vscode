<?php
/**
 * Seller Dashboard Template generated using AI
 *
 * Displays sales performance, best-selling products, recent orders,
 * and low stock alerts using Chart.js for data visualization.
 *
 * @var \App\View\AppView $this
 * @var array $summaryCards
 * @var array $salesOverTime
 * @var array $bestSellingProducts
 * @var array $recentOrders
 * @var array $lowStockProducts
 * @var int $pendingOrdersCount
 */
?>
<div class="reports dashboard content">
    <h2><?= __('Seller Dashboard') ?></h2>

    <!-- Summary Cards -->
    <div class="dashboard-cards">
        <div class="card summary-card">
            <div class="card-icon"></div>
            <div class="card-content">
                <h3><?= __('Total Products') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['totalProducts']) ?></p>
            </div>
        </div>
        <div class="card summary-card">
            <div class="card-icon"></div>
            <div class="card-content">
                <h3><?= __('Total Sales') ?></h3>
                <p class="card-value">$<?= number_format($summaryCards['totalSales'], 2) ?></p>
            </div>
        </div>
        <div class="card summary-card">
            <div class="card-icon"></div>
            <div class="card-content">
                <h3><?= __('Total Orders') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['totalOrders']) ?></p>
            </div>
        </div>
        <div class="card summary-card">
            <div class="card-icon"></div>
            <div class="card-content">
                <h3><?= __('Items Sold') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['itemsSold']) ?></p>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <?php if ($pendingOrdersCount > 0 || !empty($lowStockProducts)): ?>
        <div class="alerts-section">
            <?php if ($pendingOrdersCount > 0): ?>
                <div class="alert alert-warning">
                    <strong> <?= __('Pending Orders') ?>:</strong>
                    <?= __('You have {0} order item(s) pending delivery.', $pendingOrdersCount) ?>
                    <?= $this->Html->link(__('View Orders'), ['prefix' => 'Seller', 'controller' => 'Orders', 'action' => 'index'], ['class' => 'alert-link']) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($lowStockProducts)): ?>
                <div class="alert alert-danger">
                    <strong> <?= __('Low Stock Alert') ?>:</strong>
                    <?= __('You have {0} product(s) with low stock (≤10 items).', count($lowStockProducts)) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Charts Row -->
    <div class="charts-row">
        <!-- Sales Over Time Chart -->
        <div class="chart-container">
            <h3><?= __('Sales Performance (Last 30 Days)') ?></h3>
            <canvas id="salesChart"></canvas>
        </div>

        <!-- Best Selling Products Chart -->
        <div class="chart-container">
            <h3><?= __('Top Selling Products') ?></h3>
            <canvas id="productsChart"></canvas>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="tables-row">
        <!-- Recent Orders -->
        <div class="table-container">
            <h3><?= __('Recent Orders') ?></h3>
            <?php if (!empty($recentOrders)): ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= __('Order #') ?></th>
                        <th><?= __('Buyer') ?></th>
                        <th><?= __('Items') ?></th>
                        <th><?= __('Amount') ?></th>
                        <th><?= __('Date') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><?= $this->Html->link('#' . $order->id, ['prefix' => 'Seller', 'controller' => 'Orders', 'action' => 'view', $order->id]) ?></td>
                            <td><?= h($order->user->name ?? __('N/A')) ?></td>
                            <td><?= count($order->order_items) ?></td>
                            <td>$<?= number_format(array_sum(array_column($order->order_items, 'amount')), 2) ?></td>
                            <td><?= $order->created->format('M j, Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data"><?= __('No orders yet.') ?></p>
            <?php endif; ?>
        </div>

        <!-- Low Stock Products -->
        <div class="table-container">
            <h3><?= __('Low Stock Products') ?></h3>
            <?php if (!empty($lowStockProducts)): ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= __('Product') ?></th>
                        <th><?= __('Stock') ?></th>
                        <th><?= __('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lowStockProducts as $product): ?>
                        <tr class="<?= $product->stock <= 5 ? 'critical' : 'warning' ?>">
                            <td><?= h($product->name) ?></td>
                            <td>
                            <span class="stock-badge <?= $product->stock <= 5 ? 'critical' : 'low' ?>">
                                <?= $product->stock ?>
                            </span>
                            </td>
                            <td>
                                <?= $this->Html->link(__('Edit'), ['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'edit', $product->id], ['class' => 'button button-small']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data"><?= __('All products have adequate stock.') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Over Time Line Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($salesOverTime['labels']) ?>,
            datasets: [{
                label: '<?= __('Daily Sales ($)') ?>',
                data: <?= json_encode($salesOverTime['data']) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Best Selling Products Bar Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_map(function($p) { return $p->product_name ?? 'Unknown'; }, $bestSellingProducts)) ?>,
            datasets: [{
                label: '<?= __('Units Sold') ?>',
                data: <?= json_encode(array_map(function($p) { return (int)$p->total_quantity; }, $bestSellingProducts)) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<style>
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        font-size: 2.5rem;
    }

    .card-content h3 {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    .card-value {
        margin: 0.5rem 0 0;
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .alerts-section {
        margin-bottom: 2rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
    }

    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert-link {
        margin-left: 1rem;
        color: inherit;
        font-weight: bold;
    }

    .charts-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-container {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .chart-container h3 {
        margin: 0 0 1rem;
        font-size: 1.1rem;
        color: #333;
    }

    .tables-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }

    .table-container {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table-container h3 {
        margin: 0 0 1rem;
        font-size: 1.1rem;
        color: #333;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .table tr.critical td {
        background: #fff5f5;
    }

    .table tr.warning td {
        background: #fffbeb;
    }

    .stock-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .stock-badge.critical {
        background: #fee2e2;
        color: #dc2626;
    }

    .stock-badge.low {
        background: #fef3c7;
        color: #d97706;
    }

    .button-small {
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
    }

    .no-data {
        color: #666;
        font-style: italic;
        text-align: center;
        padding: 2rem;
    }

    @media (max-width: 768px) {
        .charts-row,
        .tables-row {
            grid-template-columns: 1fr;
        }
    }
</style>
