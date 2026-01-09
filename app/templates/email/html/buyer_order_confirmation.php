<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; font-size: 24px; margin-bottom: 20px; font-weight: 600; }
        h2 { color: #2c3e50; font-size: 18px; margin: 25px 0 15px 0; font-weight: 600; }
        p { margin: 10px 0; color: #555; }
        .greeting { margin-bottom: 20px; }
        .order-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .order-info p { margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table thead { background-color: #f8f9fa; }
        table th { padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #dee2e6; }
        table td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .total-row { text-align: right; font-weight: 600; }
        .total-amount { color: #28a745; font-size: 20px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        
        <p class="greeting">Dear <?= h($buyer->email) ?>,</p>
        
        <p>Thank you for your order! Your order has been confirmed and is being processed.</p>
        
        <h2>Order Details</h2>
        
        <div class="order-info">
            <p><strong>Order Number:</strong> #<?= $order->id ?></p>
            <p><strong>Order Date:</strong> <?= $order->created->format('F d, Y H:i') ?></p>
        </div>

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
                    <td><?= h($item->product->name ?? 'Product') ?></td>
                    <td><?= $item->quantity ?></td>
                    <td>$<?= number_format($item->price, 2) ?></td>
                    <td>$<?= number_format($item->amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-row">
            <p>Total Amount: <span class="total-amount">$<?= number_format($order->total_amount, 2) ?></span></p>
        </div>

        <p>You will receive another email when your order ships.</p>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>If you have any questions, please contact our customer support.</p>
        </div>
    </div>
</body>
</html>
