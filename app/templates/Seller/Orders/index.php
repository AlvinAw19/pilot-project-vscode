<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrderItem[] $orderItems
 */
?>
<div class="orderItems index content">
    <h3><?= __('Order Items to Fulfill') ?></h3>
    
    <?php if (!empty($orderItems)): ?>
        <?= $this->Form->create(null, ['url' => ['action' => 'updateStatus']]) ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= $this->Form->checkbox('select_all', ['id' => 'select-all']) ?></th>
                        <th><?= __('Order ID') ?></th>
                        <th><?= __('Order Date') ?></th>
                        <th><?= __('Product') ?></th>
                        <th><?= __('Buyer') ?></th>
                        <th><?= __('Quantity') ?></th>
                        <th><?= __('Amount') ?></th>
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
                            <td><?= h($item->order->buyer->name) ?></td>
                            <td><?= $this->Number->format($item->quantity) ?></td>
                            <td>$<?= $this->Number->format($item->amount, ['places' => 2]) ?></td>
                            <td>
                                <span class="badge badge-<?= h($item->delivery_status) ?>">
                                    <?= h(ucfirst($item->delivery_status)) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bulk-actions" style="margin-top: 20px; padding: 15px; background: #f8f8f8; border-radius: 4px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <label><?= __('Update selected items to:') ?></label>
                <?= $this->Form->select('delivery_status', [
                    'pending' => __('Pending'),
                    'delivering' => __('Delivering'),
                    'delivered' => __('Delivered'),
                    'canceled' => __('Canceled'),
                ], [
                    'empty' => __('-- Select Status --'),
                    'required' => true,
                    'style' => 'width: 200px;'
                ]) ?>
                <?= $this->Form->button(__('Update Status'), ['class' => 'button']) ?>
            </div>
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
    <?php else: ?>
        <div class="message" style="padding: 40px; text-align: center; background: #f8f8f8; border-radius: 4px;">
            <p><?= __('No order items to fulfill at this time.') ?></p>
        </div>
    <?php endif; ?>
</div>

<style>
.badge {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 0.9em;
}
.badge-pending { background-color: #ffc107; color: #000; }
.badge-delivering { background-color: #17a2b8; color: #fff; }
.badge-delivered { background-color: #28a745; color: #fff; }
.badge-canceled { background-color: #dc3545; color: #fff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked && selectAll) {
                selectAll.checked = false;
            }
        });
    });
});
</script>
