<?php
/**
 * The code of html template was generated using AI for better visualisation
 *
 * @var \App\Model\Entity\OrderItem $orderItem
 */
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        h2 { color: #2c3e50; }
        .info-box { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info-box p { margin: 5px 0; }
        .highlight { color: #27ae60; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <h2>New Order Received</h2>
    <p>Dear <?= h($orderItem->product->user->name) ?>,</p>
    <p>You have received a new order for your product!</p>

    <div class="info-box">
        <p><strong>Order Number:</strong> #<?= h($orderItem->order_id) ?></p>
        <p><strong>Product:</strong> <?= h($orderItem->product->name) ?></p>
        <p><strong>Quantity:</strong> <?= h($orderItem->quantity) ?></p>
        <p><strong>Amount:</strong> <span class="highlight">$<?= h(number_format($orderItem->amount, 2)) ?></span></p>
        <p><strong>Order Date:</strong> <?= h($orderItem->created->format('F d, Y H:i')) ?></p>
    </div>

    <p>Please log in to your seller dashboard to manage this order and update the delivery status.</p>

    <div class="footer">
        <p>Thank you for being a valued seller!</p>
    </div>
</div>
</body>
</html>
