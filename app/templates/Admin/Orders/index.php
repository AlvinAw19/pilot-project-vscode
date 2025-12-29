<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order[] $orders
 */
?>
<div class="orders index content">
    <h3><?= __('Order Management') ?></h3>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', __('Order ID')) ?></th>
                    <th><?= $this->Paginator->sort('buyer_id', __('Buyer')) ?></th>
                    <th><?= __('Items') ?></th>
                    <th><?= $this->Paginator->sort('total_amount', __('Total Amount')) ?></th>
                    <th><?= __('Payment Type') ?></th>
                    <th><?= $this->Paginator->sort('created', __('Order Date')) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $this->Number->format($order->id) ?></td>
                        <td>
                            <?php if ($order->buyer): ?>
                                <?= h($order->buyer->name) ?>
                            <?php else: ?>
                                <em><?= __('N/A') ?></em>
                            <?php endif; ?>
                        </td>
                        <td><?= $this->Number->format(count($order->order_items)) ?> items</td>
                        <td>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></td>
                        <td>
                            <?php if ($order->payment): ?>
                                <?= h($order->payment->payment_type) ?>
                            <?php else: ?>
                                <em><?= __('N/A') ?></em>
                            <?php endif; ?>
                        </td>
                        <td><?= h($order->created->format('Y-m-d H:i:s')) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
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
