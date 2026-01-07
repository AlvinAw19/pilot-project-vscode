<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @property \App\View\Helper\ImageHelper $Image
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Back to Catalog'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('View Category'), ['action' => 'index', '?' => ['category_id' => $product->category->id]], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="products view content">
            <h3><?= h($product->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= h($product->category->name) ?></td>
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
                        <?= $this->Image->productImageHtml(
                            $product->image_link,
                            h($product->name),
                            ['width' => 120]
                        ) ?>
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
            <div class="add-to-cart">
                <h4><?= __('Add to Cart') ?></h4>
                <?php if ($product->stock > 0): ?>
                    <?= $this->Form->create(null, [
                        'url' => ['prefix' => 'Buyer', 'controller' => 'CartItems', 'action' => 'add', $product->id]
                    ]) ?>
                    <?= $this->Form->control('quantity', [
                        'type' => 'number',
                        'min' => 1,
                        'max' => $product->stock,
                        'value' => 1,
                        'label' => __('Quantity')
                    ]) ?>
                    <?= $this->Form->button(__('Add to Cart'), ['type' => 'submit']) ?>
                    <?= $this->Form->end() ?>
                <?php else: ?>
                    <p class="out-of-stock"><strong><?= __('Out of Stock') ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

