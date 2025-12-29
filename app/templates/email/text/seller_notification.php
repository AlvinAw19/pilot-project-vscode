<?php
/**
 * Seller Notification Email (Text)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $seller
 * @var \App\Model\Entity\OrderItem[] $orderItems
 * @var \App\Model\Entity\Order $order
 */
?>
NEW ORDER RECEIVED!
===================

Dear <?= h($seller->name) ?>,

You have received a new order for your products. Please prepare the following items for shipment:

Order Information
-----------------
Order ID: #<?= h($order->id) ?>

Order Date: <?= h($order->created ? $order->created->format('F j, Y, g:i a') : 'N/A') ?>

Buyer: <?= h($order->buyer->name ?? 'N/A') ?>

<?php if (isset($order->buyer->address)): ?>
Shipping Address: <?= h($order->buyer->address) ?>

<?php endif; ?>

Your Products in This Order
----------------------------
<?php foreach ($orderItems as $item): ?>
Product: <?= h($item->product->name ?? 'Product #' . $item->product_id) ?>

Quantity: <?= h($item->quantity) ?>

Price: $<?= h(number_format($item->price, 2)) ?>

Amount: $<?= h(number_format($item->amount, 2)) ?>

Status: <?= h(ucfirst($item->delivery_status)) ?>


<?php endforeach; ?>

ACTION REQUIRED
---------------
Please log in to your seller dashboard to update the delivery status of these items.

Thank you for being a valued seller on our platform!

---
© <?= date('Y') ?> E-Commerce Platform. All rights reserved.
