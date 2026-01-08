<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\OrderItem> $orderItems
 */
?>
<div class="orders index content">
    <?= $this->Html->link(__('My Products'), ['controller' => 'Products', 'action' => 'index'], ['class' => 'button float-right']) ?>
    <h3><?= __('Order Items to Fulfill') ?></h3>

    <?= $this->Form->create(null, ['url' => ['action' => 'updateStatus']]) ?>

    <div class="bulk-actions">
        <p>
            <label><?= __('Update selected items to:') ?></label>
            <?= $this->Form->select('delivery_status', [
                'pending' => __('Pending'),
                'delivering' => __('Delivering'),
                'delivered' => __('Delivered'),
                'canceled' => __('Canceled'),
            ], [
                'empty' => __('   Select Status   '),
                'required' => true
            ]) ?>
            <?= $this->Form->button(__('Update Status')) ?>
        </p>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th></th>
                <th><?= __('Order ID') ?></th>
                <th><?= __('Order Date') ?></th>
                <th><?= __('Product') ?></th>
                <th><?= __('Buyer') ?></th>
                <th><?= __('Quantity') ?></th>
                <th><?= __('Amount') ?></th>
                <th><?= __('Payment ID') ?></th>
                <th><?= __('Delivery Status') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td>
                        <?= $this->Form->checkbox('item_ids[]', [
                            'value' => $item->id,
                            'class' => 'item-checkbox',
                            'hiddenField' => false
                        ]) ?>
                    </td>
                    <td><?= $this->Number->format($item->order_id) ?></td>
                    <td><?= h($item->order->created->format('Y-m-d H:i')) ?></td>
                    <td><?= h($item->product->name) ?></td>
                    <td><?= h($item->order->user->name) ?></td>
                    <td><?= $this->Number->format($item->quantity) ?></td>
                    <td>$<?= $this->Number->format($item->amount, ['places' => 2]) ?></td>
                    <td><?= $item->order->payment->id ?></td>
                    <td>
                        <?= h(ucfirst($item->delivery_status)) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->Form->end() ?>

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
