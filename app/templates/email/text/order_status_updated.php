Delivery Status Update

Dear <?= $buyer->name ?>,

The delivery status of your order item has been updated.

Current Status: <?= $orderItem->delivery_status ?? 'Pending' ?>


Order Details:
--------------
Order Number: #<?= $orderItem->order_id ?>

Product: <?= $orderItem->product->name ?? 'Product' ?>

Quantity: <?= $orderItem->quantity ?>

Amount: $<?= number_format($orderItem->price * $orderItem->quantity, 2) ?>


You can track your order by logging into your account.

Thank you for shopping with us!

If you have any questions, please contact our customer support.
