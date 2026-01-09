New Order Received

Dear <?= $seller->name ?>-Seller,

You have received a new order for your product!

Order Information:
------------------
Order Number: #<?= $orderItem->order->id ?>

Product: <?= $orderItem->product->name ?>

Quantity: <?= $orderItem->quantity ?>

Amount: $<?= number_format($orderItem->amount, 2) ?>

Order Date: <?= $orderItem->created->format('F d, Y H:i') ?>


Please log in to your seller dashboard to manage this order and update the delivery status.

Thank you for being a valued seller!
