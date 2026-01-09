Order Confirmation

Dear <?= $buyer->email ?>,

Thank you for your order! Your order has been confirmed and is being processed.

Order Details
--------------
Order Number: #<?= $order->id ?>

Order Date: <?= $order->created->format('F d, Y H:i') ?>


Product Details:
----------------
<?php foreach ($order->order_items as $item): ?>
<?= $item->product->name ?? 'Product' ?>
Quantity: <?= $item->quantity ?>
Price: $<?= number_format($item->price, 2) ?>
Total: $<?= number_format($item->amount, 2) ?>

<?php endforeach; ?>
Total Amount: $<?= number_format($order->total_amount, 2) ?>


You will receive another email when your order ships.

Thank you for shopping with us!

If you have any questions, please contact our customer support.
