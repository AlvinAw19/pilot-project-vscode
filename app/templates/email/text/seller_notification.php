New Order Received
==================

Dear <?= h($orderItem->product->user->name) ?>,

You have received a new order for your product!

Order Details
-------------
Order Number: #<?= h($orderItem->order_id) ?>

Product: <?= h($orderItem->product->name) ?>

Quantity: <?= h($orderItem->quantity) ?>

Amount: $<?= h(number_format($orderItem->amount, 2)) ?>

Order Date: <?= h($orderItem->created->format('F d, Y H:i')) ?>


Please log in to your seller dashboard to manage this order and update the delivery status.

Thank you for being a valued seller!
