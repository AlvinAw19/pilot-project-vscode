<?php
/**
 * Admin Dashboard Template generated using AI
 *
 * Displays platform-wide metrics, revenue trends, user growth,
 * top sellers, and system health indicators using Chart.js.
 *
 * @var \App\View\AppView $this
 * @var array $summaryCards
 * @var array $revenueOverTime
 * @var array $ordersOverTime
 * @var array $userGrowth
 * @var array $topSellers
 * @var array $topCategories
 * @var array $systemHealth
 * @var array $recentOrders
 */
?>
<div class="reports dashboard content">
    <h2><?= __('Admin Dashboard') ?></h2>

    <!-- Summary Cards -->
    <div class="dashboard-cards">
        <div class="card summary-card revenue">
            <div class="card-content">
                <h3><?= __('Total Revenue') ?></h3>
                <p class="card-value">$<?= number_format($summaryCards['totalRevenue'], 2) ?></p>
            </div>
        </div>
        <div class="card summary-card orders">
            <div class="card-content">
                <h3><?= __('Total Orders') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['totalOrders']) ?></p>
            </div>
        </div>
        <div class="card summary-card users">
            <div class="card-content">
                <h3><?= __('Total Users') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['totalUsers']) ?></p>
            </div>
        </div>
        <div class="card summary-card products">
            <div class="card-content">
                <h3><?= __('Total Products') ?></h3>
                <p class="card-value"><?= number_format($summaryCards['totalProducts']) ?></p>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="secondary-stats">
        <div class="stat-group">
            <h4><?= __('User Stats') ?></h4>
            <div class="stat-cards">
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Sellers') ?></span>
                        <span class="stat-value"><?= number_format($summaryCards['totalSellers']) ?></span>
                    </div>
                </div>
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Buyers') ?></span>
                        <span class="stat-value"><?= number_format($summaryCards['totalBuyers']) ?></span>
                    </div>
                </div>
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Admins') ?></span>
                        <span class="stat-value"><?= number_format($summaryCards['totalAdmins']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-group">
            <h4><?= __('System Statistic') ?></h4>
            <div class="stat-cards">
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Pending Orders') ?></span>
                        <span class="stat-value"><?= $systemHealth['pendingOrders'] ?></span>
                    </div>
                </div>
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Low Stock') ?></span>
                        <span class="stat-value"><?= $systemHealth['lowStockProducts'] ?></span>
                    </div>
                </div>
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Inactive Sellers') ?></span>
                        <span class="stat-value"><?= $systemHealth['inactiveSellers'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-group">
            <h4><?= __('Today') ?></h4>
            <div class="stat-cards">
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('New Users') ?></span>
                        <span class="stat-value"><?= $systemHealth['newUsersToday'] ?></span>
                    </div>
                </div>
                <div class="stat-card system">
                    <div class="stat-info">
                        <span class="stat-label"><?= __('Orders') ?></span>
                        <span class="stat-value"><?= $systemHealth['ordersToday'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Revenue & Orders -->
    <div class="charts-row">
        <div class="chart-container">
            <h3><?= __('Revenue Trend (Last 30 Days)') ?></h3>
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="chart-container">
            <h3><?= __('Orders Trend (Last 30 Days)') ?></h3>
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    <!-- Charts Row 2: User Growth & Categories -->
    <div class="charts-row">
        <div class="chart-container">
            <h3><?= __('User Registrations (Last 30 Days)') ?></h3>
            <canvas id="userGrowthChart"></canvas>
        </div>
        <div class="chart-container">
            <h3><?= __('Top Categories by Revenue') ?></h3>
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="tables-row">
        <!-- Top Sellers -->
        <div class="table-container">
            <h3><?= __('Top Sellers') ?></h3>
            <?php if (!empty($topSellers)): ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= __('Seller') ?></th>
                        <th><?= __('Orders') ?></th>
                        <th><?= __('Revenue') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($topSellers as $seller): ?>
                        <tr>
                            <td>
                                <?= $this->Html->link(
                                    h($seller->user_name),
                                    ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'view', $seller->user_id ?? 0]
                                ) ?>
                            </td>
                            <td><?= number_format((int)$seller->total_orders) ?></td>
                            <td>RM<?= number_format((float)$seller->total_revenue, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data"><?= __('No sales data yet.') ?></p>
            <?php endif; ?>
        </div>

        <!-- Recent Orders -->
        <div class="table-container">
            <h3><?= __('Recent Orders') ?></h3>
            <?php if (!empty($recentOrders)): ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= __('Order') ?></th>
                        <th><?= __('Buyer') ?></th>
                        <th><?= __('Amount') ?></th>
                        <th><?= __('Payment') ?></th>
                        <th><?= __('Date') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><?= $this->Html->link('#' . $order->id, ['prefix' => 'Admin', 'controller' => 'Orders', 'action' => 'view', $order->id]) ?></td>
                            <td><?= h($order->user->name) ?></td>
                            <td>RM<?= number_format((float)$order->total_amount, 2) ?></td>
                            <td><?= h($order->payment->payment_type) ?></td>
                            <td><?= $order->created->format('M j, Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data"><?= __('No orders yet.') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Revenue Over timeline Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revenueOverTime['labels']) ?>,
            datasets: [{
                label: '<?= __('Daily Revenue (RM)') ?>',
                data: <?= json_encode($revenueOverTime['data']) ?>,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'RM' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Orders Over Timeline Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($ordersOverTime['labels']) ?>,
            datasets: [{
                label: '<?= __('Orders') ?>',
                data: <?= json_encode($ordersOverTime['data']) ?>,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // User Growth Stacked Bar Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($userGrowth['labels']) ?>,
            datasets: [{
                label: '<?= __('New Users') ?>',
                data: <?= json_encode($userGrowth['data']) ?>,
                backgroundColor: 'rgba(168, 85, 247, 0.7)',
                borderColor: 'rgb(168, 85, 247)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.1,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Top Categories Doughnut Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_map(function($c) { return $c->category_name; }, $topCategories)) ?>,
            datasets: [{
                data: <?= json_encode(array_map(function($c) { return (float)$c->total_revenue; }, $topCategories)) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': RM' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

<style>
    .reports.dashboard h2 {
        margin-bottom: 1rem;
    }

    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: #fff;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #ddd;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .summary-card.revenue { border-left-color: #22c55e; }
    .summary-card.orders { border-left-color: #3b82f6; }
    .summary-card.users { border-left-color: #a855f7; }
    .summary-card.products { border-left-color: #f59e0b; }

    .card-content h3 {
        margin: 0;
        font-size: 1rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .card-value {
        margin: 0.5rem 0 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
    }

    .secondary-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-group {
        background: #fff;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-group h4 {
        margin: 0 0 1rem;
        font-size: 1.25rem;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
    }

    .stat-cards {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .stat-card {
        padding: 0.75rem 1rem;
        border-radius: 6px;
        background: #f8f9fa;
    }

    .stat-card.system {
        background: #f1f5f9;
        color: #334155;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 0.15rem;
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
        font-size: 1.3rem;
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
        font-size: 1.3rem;
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

    .no-data {
        color: #666;
        font-style: italic;
        text-align: center;
        padding: 2rem;
    }
</style>
