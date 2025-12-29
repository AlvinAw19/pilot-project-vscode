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
            <?= $this->Html->link(__('Back to Orders'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Continue Shopping'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="orders view content">
            <h3><?= __('Order Details - #{0}', $order->id) ?></h3>
            
            <div class="order-info" style="background: #f8f8f8; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <table>
                    <tr>
                        <th style="width: 150px;"><?= __('Order ID') ?></th>
                        <td><?= $this->Number->format($order->id) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Order Date') ?></th>
                        <td><?= h($order->created->format('Y-m-d H:i:s')) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Total Amount') ?></th>
                        <td><strong>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></strong></td>
                    </tr>
                    <tr>
                        <th><?= __('Payment Type') ?></th>
                        <td>
                            <?php if ($order->payment): ?>
                                <?= h($order->payment->payment_type) ?>
                            <?php else: ?>
                                <em><?= __('Not specified') ?></em>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <h4><?= __('Order Items') ?></h4>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><?= __('Product') ?></th>
                            <th><?= __('Price') ?></th>
                            <th><?= __('Quantity') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Delivery Status') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order->order_items as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item->product): ?>
                                        <?= $this->Html->link(
                                            h($item->product->name),
                                            ['controller' => 'Catalogs', 'action' => 'view', $item->product->slug, 'prefix' => false]
                                        ) ?>
                                    <?php else: ?>
                                        <?= __('Product #{0}', $item->product_id) ?>
                                    <?php endif; ?>
                                </td>
                                <td>$<?= $this->Number->format($item->price, ['places' => 2]) ?></td>
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
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong><?= __('Total:') ?></strong></td>
                            <td colspan="2"><strong>$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
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
