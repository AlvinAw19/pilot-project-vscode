<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Orders'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="orders view content">
            <h3>Order #<?= h($order->id) ?></h3>

            <table>
                <tr>
                    <th>Payment ID</th>
                    <td><?= h($order->payment->id) ?></td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></td>
                </tr>
                <tr>
                    <th>Paid at</th>
                    <td><?= h($order->created) ?></td>
                </tr>
            </table>
        </div>

        <br>

        <div>
            <h4>Order Items</h4>

            <table>
                <tr>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Delivery Status</th>
                </tr>

                <?php foreach ($order->order_items as $item) : ?>
                    <tr>
                        <td><?= $this->Html->link($item->product->name, ['prefix' => false, 'controller' => 'Catalogs', 'action' => 'view', $item->product->slug]) ?></td>
                        <td><?= h($item->product->user->name) ?></td>
                        <td><?= h($item->product->user->address) ?></td>
                        <td>$<?= $this->Number->format($item->price, ['places' => 2]) ?></td>
                        <td><?= h($item->quantity) ?></td>
                        <td>
                            $<?= $this->Number->format(
                                $item->price * $item->quantity,
                                ['places' => 2]
                            ) ?>
                        </td>
                        <td><?= h($item->delivery_status) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
