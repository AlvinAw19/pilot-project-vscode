<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
?>
<div class="products index content">
    <div style="display:flex; align-items:center;">
        <h3 style="margin-right:auto;">
            <?= __('Products') ?>
        </h3>

        <div style="display:flex; gap:10px;">
            <?= $this->Html->link(
                __('Users'),
                ['controller' => 'Users', 'action' => 'index'],
                ['class' => 'button']
            ) ?>
            <?= $this->Html->link(
                __('Categories'),
                ['controller' => 'Categories', 'action' => 'index'],
                ['class' => 'button']
            ) ?>
            <?= $this->Html->link(
                __('Orders'),
                ['controller' => 'Orders', 'action' => 'index'],
                ['class' => 'button']
            ) ?>
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('category_id') ?></th>
                    <th><?= $this->Paginator->sort('seller_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('slug') ?></th>
                    <th><?= $this->Paginator->sort('stock') ?></th>
                    <th><?= $this->Paginator->sort('price') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $this->Number->format($product->id) ?></td>
                        <td><?= $product->category->name ?></td>
                        <td><?= $product->user->name ?></td>
                        <td><?= h($product->name) ?></td>
                        <td><?= h($product->slug) ?></td>
                        <td><?= $this->Number->format($product->stock) ?></td>
                        <td><?= $this->Number->format($product->price) ?></td>
                        <td><?= h($product->created) ?></td>
                        <td><?= h($product->modified) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $product->slug]) ?>
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
