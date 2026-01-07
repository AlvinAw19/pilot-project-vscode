<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var iterable<\App\Model\Entity\Category> $categories
 * @var string|null $searchTerm
 * @var int|null $categoryId
 * @var \App\Model\Entity\Category|null $selectedCategory
 * @property \App\View\Helper\ImageHelper $Image
 */
?>
<div class="catalogs catalog content">
    <div class="catalog-header">
        <h3><?= __('Product Catalog') ?></h3>
        <?= $this->Html->link(
            __('My Cart'),
            ['prefix' => 'Buyer', 'controller' => 'CartItems', 'action' => 'index'],
            ['class' => 'button cart-button']
        ) ?>
    </div>

    <!-- Search Form -->
    <div class="search">
        <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>
        <fieldset>
            <?= $this->Form->control('q', [
                'label' => __('Search'),
                'placeholder' => __('Search products by name or description...'),
                'value' => $searchTerm ?? ''
            ]) ?>
            <?= $this->Form->button(__('Search'), ['type' => 'submit']) ?>
            <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'button']) ?>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>

    <!-- Category Filter -->
    <div class="categories">
        <h4><?= __('Categories') ?></h4>

        <?= $this->Html->link(__('All'), ['action' => 'index'], [
            'class' => 'button' . (!isset($categoryId) ? ' selected' : '')
        ]) ?>

        <?php
        $others = null;
        ?>

        <?php foreach ($categories as $category): ?>
            <?php
            if ($category->name === 'Others') {
                $others = $category;
                continue;
            }
            ?>
            <?= $this->Html->link(
                h($category->name),
                ['action' => 'index', '?' => ['category_id' => $category->id]],
                ['class' => 'button' . ($categoryId == $category->id ? ' selected' : '')]
            ) ?>
        <?php endforeach; ?>

        <?php if ($others): ?>
            <?= $this->Html->link(
                h($others->name),
                ['action' => 'index', '?' => ['category_id' => $others->id]],
                ['class' => 'button' . ($categoryId == $others->id ? ' selected' : '')]
            ) ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($searchTerm)): ?>
        <p class="search-info"><?= __('Search: "{0}"', h($searchTerm)) ?></p>
    <?php endif; ?>

    <?php if (isset($categoryId)): ?>
        <?php
        $selectedCategory = $categories->firstMatch(['id' => $categoryId]);
        ?>
        <?php if ($selectedCategory): ?>
            <p class="category-info"><?= __('Category: {0}', h($selectedCategory->name)) ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Products -->
    <?php if ($products->count() > 0): ?>
        <div class="products index content">
            <table>
                <thead>
                <tr>
                    <th><?= __('Image') ?></th>
                    <th><?= $this->Paginator->sort('name', __('Product Name')) ?></th>
                    <th><?= $this->Paginator->sort('category_id', __('Category')) ?></th>
                    <th><?= $this->Paginator->sort('price', __('Price')) ?></th>
                    <th><?= $this->Paginator->sort('stock', __('Stock')) ?></th>
                    <th class="action"><?= __('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?= $this->Image->productImageHtml(
                                $product->image_link,
                                h($product->name),
                                ['width' => 120]
                            ) ?>
                        </td>
                        <td><?= $this->Html->link(h($product->name), ['action' => 'view', $product->slug]) ?></td>
                        <td><?= h($product->category->name) ?></td>
                        <td><?= $this->Number->format($product->price, ['places' => 2]) ?></td>
                        <td><?= $this->Number->format($product->stock) ?></td>
                        <td class="action">
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
    <?php else: ?>
        <div class="message">
            <p><?= __('No products found.') ?></p>
        </div>
    <?php endif; ?>
</div>

