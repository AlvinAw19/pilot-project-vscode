<?php
/**
 * Order Confirmation Email (HTML)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\User $buyer
 * @var \App\Model\Entity\OrderItem[] $orderItems
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
            background-color: #1b89bc;
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
        .total {
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
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
        <h1>Order Confirmation</h1>
    </div>

    <div class="content">
        <p>Dear <?= h($buyer->name) ?>,</p>
        
        <p>Thank you for your order! We're pleased to confirm that we've received your order and it's being processed.</p>

        <div class="order-details">
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> #<?= h($order->id) ?></p>
            <p><strong>Order Date:</strong> <?= h($order->created->format('F j, Y, g:i a')) ?></p>
            <?php if ($order->payment): ?>
                <p><strong>Payment Method:</strong> <?= h($order->payment->payment_type) ?></p>
            <?php endif; ?>
        </div>

        <h3>Items Ordered:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= h($item->product->name ?? 'Product #' . $item->product_id) ?></td>
                        <td><?= h($item->quantity) ?></td>
                        <td>$<?= h(number_format($item->price, 2)) ?></td>
                        <td>$<?= h(number_format($item->amount, 2)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                    <td class="total">$<?= h(number_format($order->total_amount, 2)) ?></td>
                </tr>
            </tfoot>
        </table>

        <p>You can track your order status by logging into your account.</p>
        
        <p>If you have any questions about your order, please don't hesitate to contact us.</p>
        
        <p>Thank you for shopping with us!</p>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> E-Commerce Platform. All rights reserved.</p>
    </div>
</body>
</html>
