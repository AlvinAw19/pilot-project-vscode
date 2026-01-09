<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Received</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; font-size: 24px; margin-bottom: 20px; font-weight: 600; }
        p { margin: 10px 0; color: #555; }
        .greeting { margin-bottom: 20px; }
        .order-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .order-info p { margin: 8px 0; }
        .info-label { font-weight: 600; display: inline-block; min-width: 120px; }
        .amount { color: #28a745; font-weight: 600; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Order Received</h1>
        
        <p class="greeting">Dear <?= h($seller->name) ?>-Seller,</p>
        
        <p>You have received a new order for your product!</p>
        
        <div class="order-info">
            <p><span class="info-label">Order Number:</span> #<?= $orderItem->order->id ?></p>
            <p><span class="info-label">Product:</span> <?= h($orderItem->product->name) ?></p>
            <p><span class="info-label">Quantity:</span> <?= $orderItem->quantity ?></p>
            <p><span class="info-label">Amount:</span> <span class="amount">$<?= number_format($orderItem->amount, 2) ?></span></p>
            <p><span class="info-label">Order Date:</span> <?= $orderItem->created->format('F d, Y H:i') ?></p>
        </div>

        <p>Please log in to your seller dashboard to manage this order and update the delivery status.</p>

        <div class="footer">
            <p>Thank you for being a valued seller!</p>
        </div>
    </div>
</body>
</html>
