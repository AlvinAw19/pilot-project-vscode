Delivery Status Update
======================

Dear <?= h($orderItem->order->user->name) ?>,

The delivery status of your order item has been updated.

Current Status: <?= h(strtoupper($orderItem->delivery_status)) ?>


Order Details
-------------
Order Number: #<?= h($orderItem->order_id) ?>

Product: <?= h($orderItem->product->name) ?>

Quantity: <?= h($orderItem->quantity) ?>

Amount: $<?= h(number_format($orderItem->amount, 2)) ?>


You can track your order by logging into your account.

Thank you for shopping with us!
If you have any questions, please contact our customer support.
