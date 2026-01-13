<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Status Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; font-size: 24px; margin-bottom: 20px; font-weight: 600; }
        p { margin: 10px 0; color: #555; }
        .greeting { margin-bottom: 20px; }
        .status-box { background: #d4edda; padding: 30px; border-radius: 5px; margin: 25px 0; text-align: center; }
        .status-label { color: #666; font-size: 14px; margin-bottom: 10px; }
        .status-value { color: #28a745; font-size: 28px; font-weight: 700; }
        .order-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .order-info p { margin: 8px 0; }
        .info-label { font-weight: 600; display: inline-block; min-width: 100px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delivery Status Update</h1>
        
        <p class="greeting">Dear <?= h($buyer->name) ?>,</p>
        
        <p>The delivery status of your order item has been updated.</p>
        
        <div class="status-box">
            <div class="status-label">Current Status:</div>
            <div class="status-value"><?= h($orderItem->delivery_status ?? 'Pending') ?></div>
        </div>

        <div class="order-info">
            <p><span class="info-label">Order Number:</span> #<?= $orderItem->order_id ?></p>
            <p><span class="info-label">Product:</span> <?= h($orderItem->product->name ?? 'Product') ?></p>
            <p><span class="info-label">Quantity:</span> <?= $orderItem->quantity ?></p>
            <p><span class="info-label">Amount:</span> $<?= number_format($orderItem->price * $orderItem->quantity, 2) ?></p>
        </div>

        <p>You can track your order by logging into your account.</p>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>If you have any questions, please contact our customer support.</p>
        </div>
    </div>
</body>
</html>
