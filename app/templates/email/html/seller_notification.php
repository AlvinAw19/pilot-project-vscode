<?php
/**
 * Seller Notification Email (HTML)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $seller
 * @var \App\Model\Entity\OrderItem[] $orderItems
 * @var \App\Model\Entity\Order $order
 */
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f8f8;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f0f0f0;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Order Received!</h1>
    </div>

    <div class="content">
        <p>Dear <?= h($seller->name) ?>,</p>
        
        <p>You have received a new order for your products. Please prepare the following items for shipment:</p>

        <div class="order-details">
            <h2>Order Information</h2>
            <p><strong>Order ID:</strong> #<?= h($order->id) ?></p>
            <p><strong>Order Date:</strong> <?= h($order->created ? $order->created->format('F j, Y, g:i a') : 'N/A') ?></p>
            <p><strong>Buyer:</strong> <?= h($order->buyer->name ?? 'N/A') ?></p>
            <?php if (isset($order->buyer->address)): ?>
                <p><strong>Shipping Address:</strong> <?= h($order->buyer->address) ?></p>
            <?php endif; ?>
        </div>

        <h3>Your Products in This Order:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= h($item->product->name ?? 'Product #' . $item->product_id) ?></td>
                        <td><?= h($item->quantity) ?></td>
                        <td>$<?= h(number_format($item->price, 2)) ?></td>
                        <td>$<?= h(number_format($item->amount, 2)) ?></td>
                        <td><?= h(ucfirst($item->delivery_status)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="alert">
            <strong>Action Required:</strong> Please log in to your seller dashboard to update the delivery status of these items.
        </div>
        
        <p>Thank you for being a valued seller on our platform!</p>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> E-Commerce Platform. All rights reserved.</p>
    </div>
</body>
</html>
