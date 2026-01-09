Delivery Status Update

Dear <?= $buyer->name ?>,

The delivery status of your order item has been updated.

Current Status: <?= $order->order_items[0]->delivery_status ?? 'Pending' ?>


Order Details:
--------------
Order Number: #<?= $order->id ?>

Product: <?= $order->order_items[0]->product->name ?? 'Product' ?>

Quantity: <?= $order->order_items[0]->quantity ?>

Amount: $<?= number_format($order->total_amount, 2) ?>


You can track your order by logging into your account.

Thank you for shopping with us!

If you have any questions, please contact our customer support.
