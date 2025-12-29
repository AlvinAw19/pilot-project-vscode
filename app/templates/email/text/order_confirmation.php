<?php
/**
 * Order Confirmation Email (Text)
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 * @var \App\Model\Entity\User $buyer
 * @var \App\Model\Entity\OrderItem[] $orderItems
 */
?>
ORDER CONFIRMATION
==================

Dear <?= h($buyer->name) ?>,

Thank you for your order! We're pleased to confirm that we've received your order and it's being processed.

Order Details
-------------
Order ID: #<?= h($order->id) ?>

Order Date: <?= h($order->created->format('F j, Y, g:i a')) ?>

<?php if ($order->payment): ?>
Payment Method: <?= h($order->payment->payment_type) ?>

<?php endif; ?>

Items Ordered
-------------
<?php foreach ($orderItems as $item): ?>
- <?= h($item->product->name ?? 'Product #' . $item->product_id) ?>

  Quantity: <?= h($item->quantity) ?>

  Price: $<?= h(number_format($item->price, 2)) ?>

  Total: $<?= h(number_format($item->amount, 2)) ?>


<?php endforeach; ?>

Total Amount: $<?= h(number_format($order->total_amount, 2)) ?>


You can track your order status by logging into your account.

If you have any questions about your order, please don't hesitate to contact us.

Thank you for shopping with us!

---
© <?= date('Y') ?> E-Commerce Platform. All rights reserved.
