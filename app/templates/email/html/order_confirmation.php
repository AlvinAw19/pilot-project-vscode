<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .total { font-size: 18px; font-weight: bold; color: #27ae60; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <h2>Order Confirmation</h2>
    <p>Dear <?= h($order->user->name) ?>,</p>
    <p>Thank you for your order! Your order has been confirmed and is being processed.</p>

    <h3>Order Details</h3>
    <p><strong>Order Number:</strong> #<?= h($order->id) ?></p>
    <p><strong>Order Date:</strong> <?= h($order->created->format('F d, Y H:i')) ?></p>

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
        <?php foreach ($order->order_items as $item): ?>
            <tr>
                <td><?= h($item->product->name) ?></td>
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

    <p>You will receive another email when your order ships.</p>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>If you have any questions, please contact our customer support.</p>
    </div>
</div>
</body>
</html>
