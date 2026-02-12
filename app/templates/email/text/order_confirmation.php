<?php
/**
 * @var \App\Model\Entity\Order $order
 */
?>

Order Confirmation
==================

Dear <?= h($order->user->name) ?>,

Thank you for your order! Your order has been confirmed and is being processed.

Order Details
-------------
Order Number: #<?= h($order->id) ?>

Order Date: <?= h($order->created->format('F d, Y H:i')) ?>


Order Items:
<?php foreach ($order->order_items as $item): ?>
    - <?= h($item->product->name) ?> x <?= h($item->quantity) ?> - $<?= h(number_format($item->amount, 2)) ?>

<?php endforeach; ?>

Total Amount: $<?= h(number_format($order->total_amount, 2)) ?>


You will receive another email when your order ships.

Thank you for shopping with us!
If you have any questions, please contact our customer support.
