<?php
/**
 * Delivery Status Update Email (HTML)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrderItem $orderItem
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\User $buyer
 * @var \App\Model\Entity\Product $product
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
            background-color: #17a2b8;
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
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-delivering {
            background-color: #17a2b8;
            color: #fff;
        }
        .status-delivered {
            background-color: #28a745;
            color: #fff;
        }
        .status-canceled {
            background-color: #dc3545;
            color: #fff;
        }
        .product-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
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
        <h1>Delivery Status Update</h1>
    </div>

    <div class="content">
        <p>Dear <?= h($buyer->name) ?>,</p>
        
        <p>The delivery status of an item in your order has been updated.</p>

        <div class="product-details">
            <h2>Order & Product Information</h2>
            <p><strong>Order ID:</strong> #<?= h($order->id) ?></p>
            <p><strong>Product:</strong> <?= h($product->name ?? 'Product #' . $orderItem->product_id) ?></p>
            <p><strong>Quantity:</strong> <?= h($orderItem->quantity) ?></p>
            <p><strong>Amount:</strong> $<?= h(number_format($orderItem->amount, 2)) ?></p>
        </div>

        <p><strong>Updated Status:</strong></p>
        <div class="status-badge status-<?= h($orderItem->delivery_status) ?>">
            <?= h(ucfirst($orderItem->delivery_status)) ?>
        </div>

        <?php if ($orderItem->delivery_status === 'delivering'): ?>
            <p>Your item is on its way! It will be delivered soon.</p>
        <?php elseif ($orderItem->delivery_status === 'delivered'): ?>
            <p>Your item has been delivered. We hope you enjoy your purchase!</p>
        <?php elseif ($orderItem->delivery_status === 'canceled'): ?>
            <p>This item has been canceled. Please contact us if you have any questions.</p>
        <?php endif; ?>

        <p>You can view your complete order details by logging into your account.</p>
        
        <p>Thank you for shopping with us!</p>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> E-Commerce Platform. All rights reserved.</p>
    </div>
</body>
</html>
