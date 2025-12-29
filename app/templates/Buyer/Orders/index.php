<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 */
?>
<div class="orders index content">
    <h3><?= __('My Orders') ?></h3>
    
    <?php if ($orders->count() > 0): ?>
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
                            <td><?= h($order->created->format('Y-m-d H:i')) ?></td>
                            <td><?= count($order->order_items) ?> item(s)</td>
                            <td>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></td>
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
    <?php else: ?>
        <div class="message" style="padding: 40px; text-align: center; background: #f8f8f8; border-radius: 4px;">
            <p><?= __('You have no orders yet.') ?></p>
            <p>
                <?= $this->Html->link(__('Start Shopping'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
            </p>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 20px;">
        <?= $this->Html->link(__('Continue Shopping'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
    </div>
</div>
