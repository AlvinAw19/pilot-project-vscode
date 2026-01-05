<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order[] $orders
 */
?>
<div>
    <h3><?= __('Order Management') ?></h3>

    <table>
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', __('Order ID')) ?></th>
                <th><?= $this->Paginator->sort('buyer_id', __('Buyer')) ?></th>
                <th><?= __('Items') ?></th>
                <th><?= $this->Paginator->sort('total_amount', __('Total Amount')) ?></th>
                <th><?= __('Payment Type') ?></th>
                <th><?= $this->Paginator->sort('created', __('Order Date')) ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $this->Number->format($order->id) ?></td>
                    <td><?= $this->Html->link($order->user->name, ['controller' => 'Users', 'action' => 'view', $order->user->id]) ?></td>
                    <td><?= $this->Number->format(count($order->order_items)) ?> items</td>
                    <td>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></td>
                    <td><?= h($order->payment->payment_type) ?></td>
                    <td><?= h($order->created->format('Y-m-d H:i:s')) ?></td>
                    <td>
                        <?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
