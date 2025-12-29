<?php
/**
 * Delivery Status Update Email (Text)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrderItem $orderItem
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\User $buyer
 * @var \App\Model\Entity\Product $product
 */
?>
DELIVERY STATUS UPDATE
======================

Dear <?= h($buyer->name) ?>,

The delivery status of an item in your order has been updated.

Order & Product Information
----------------------------
Order ID: #<?= h($order->id) ?>

Product: <?= h($product->name ?? 'Product #' . $orderItem->product_id) ?>

Quantity: <?= h($orderItem->quantity) ?>

Amount: $<?= h(number_format($orderItem->amount, 2)) ?>


Updated Status: <?= h(strtoupper($orderItem->delivery_status)) ?>


<?php if ($orderItem->delivery_status === 'delivering'): ?>
Your item is on its way! It will be delivered soon.
<?php elseif ($orderItem->delivery_status === 'delivered'): ?>
Your item has been delivered. We hope you enjoy your purchase!
<?php elseif ($orderItem->delivery_status === 'canceled'): ?>
This item has been canceled. Please contact us if you have any questions.
<?php endif; ?>


You can view your complete order details by logging into your account.

Thank you for shopping with us!

---
© <?= date('Y') ?> E-Commerce Platform. All rights reserved.
