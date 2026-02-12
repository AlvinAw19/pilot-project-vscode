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
    <title>Delivery Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        h2 { color: #2c3e50; }
        .status-box { background-color: #e8f5e9; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center; }
        .status { font-size: 24px; font-weight: bold; color: #27ae60; text-transform: capitalize; }
        .info-box { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info-box p { margin: 5px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <h2>Delivery Status Update</h2>
    <p>Dear <?= h($orderItem->order->user->name) ?>,</p>
    <p>The delivery status of your order item has been updated.</p>

    <div class="status-box">
        <p style="margin: 0; color: #666;">Current Status:</p>
        <p class="status"><?= h(ucfirst($orderItem->delivery_status)) ?></p>
    </div>

    <div class="info-box">
        <p><strong>Order Number:</strong> #<?= h($orderItem->order_id) ?></p>
        <p><strong>Product:</strong> <?= h($orderItem->product->name) ?></p>
        <p><strong>Quantity:</strong> <?= h($orderItem->quantity) ?></p>
        <p><strong>Amount:</strong> $<?= h(number_format($orderItem->amount, 2)) ?></p>
    </div>

    <p>You can track your order by logging into your account.</p>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>If you have any questions, please contact our customer support.</p>
    </div>
</div>
</body>
</html>
