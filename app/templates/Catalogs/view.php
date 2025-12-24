<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Back to Catalog'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?php if ($product->has('category')): ?>
                <?= $this->Html->link(__('View Category'), ['action' => 'index', '?' => ['category_id' => $product->category->id]], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="products view content">
            <h3><?= h($product->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= $product->has('category') ? h($product->category->name) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($product->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Slug') ?></th>
                    <td><?= h($product->slug) ?></td>
                </tr>
                <tr>
                    <th><?= __('Image') ?></th>
                    <td>
                        <?php if (!empty($product->image_link)): ?>
                            <?= $this->Html->image($product->image_link, ['width' => 300]) ?>
                        <?php else: ?>
                            <em><?= __('No image available') ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Price') ?></th>
                    <td>$<?= $this->Number->format($product->price, ['places' => 2]) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stock') ?></th>
                    <td><?= $this->Number->format($product->stock) ?> units</td>
                </tr>
                <?php if ($product->has('user')): ?>
                    <tr>
                        <th><?= __('Seller') ?></th>
                        <td><?= h($product->user->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Seller Address') ?></th>
                        <td><?= h($product->user->address) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Seller Description') ?></th>
                        <td><?= h($product->user->description) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th><?= __('Listed Date') ?></th>
                    <td><?= h($product->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Updated') ?></th>
                    <td><?= h($product->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($product->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>

