<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 */
?>
<div class="orders index content">
    <h3><?= __('My Orders') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= __('Order ID') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Items') ?></th>
                <th><?= __('Total Amount') ?></th>
                <th><?= __('Payment') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $this->Number->format($order->id) ?></td>

                    <td>
                        <?= h($order->created->format('Y-m-d H:i')) ?>
                    </td>

                    <td>
                        <?= count($order->order_items) ?> <?= __('item(s)') ?>
                    </td>

                    <td>
                        $<?= $this->Number->format($order->total_amount, ['places' => 2]) ?>
                    </td>

                    <td>
                        <?php if ($order->payment): ?>
                            <?= h($order->payment->payment_type) ?>
                        <?php else: ?>
                            <em><?= __('Pending') ?></em>
                        <?php endif; ?>
                    </td>

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
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
    </div>
    <?= $this->Html->link(
        __('Continue Shopping'),
        ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false],
        ['class' => 'button']
    ) ?>
</div>
