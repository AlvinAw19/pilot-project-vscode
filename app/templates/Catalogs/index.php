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
    <div style="display:flex; align-items:center;">
        <h3 style="margin-right:auto;">
            <?= __('Product Catalog') ?>
        </h3>

        <div style="display:flex; gap:10px;">
            <?= $this->Html->link(
                __('My Order'),
                ['prefix' => 'Buyer', 'controller' => 'Orders', 'action' => 'index'],
                ['class' => 'button order-button']
            ) ?>
            <?= $this->Html->link(
                __('My Cart'),
                ['prefix' => 'Buyer', 'controller' => 'CartItems', 'action' => 'index'],
                ['class' => 'button cart-button']
            ) ?>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'url' => ['action' => 'index'],
            'valueSources' => ['query', 'context']
        ]) ?>
        <fieldset>
            <?= $this->Form->control('search', [
                'label' => false,
                'placeholder' => __('Search products by name or description...'),
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
            'class' => 'button' . (!$categoryId ? ' selected' : '')
        ]) ?>

        <?php
        $others = null;
        foreach ($categories as $category) {
            if ($category->name === 'Others') {
                $others = $category;
            }
        }
        ?>

        <?php foreach ($categories as $category): ?>
            <?php if ($category->name === 'Others') continue; ?>
            <?= $this->Html->link(
                $category->name,
                ['action' => 'index', '?' => ['category_id' => $category->id]],
                ['class' => 'button' . ($categoryId == $category->id ? ' selected' : ''), 'escape' => true]
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

    <?php if ($selectedCategory): ?>
        <p class="category-info"><?= __('Category: {0}', h($selectedCategory->name)) ?></p>
    <?php endif; ?>

    <!-- Products -->
    <?php if ($products->count() > 0): ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="<?= $this->Url->build(['action' => 'view', $product->slug]) ?>" class="product-link">
                        <div class="product-image">
                            <?= $this->Image->productImageHtml(
                                $product->image_link,
                                h($product->name),
                                ['width' => 280]
                            ) ?>
                        </div>
                        <div class="product-info">
                            <h4 class="product-name"><?= h($product->name) ?></h4>
                            <p class="product-price">RM<?= $this->Number->format($product->price, ['places' => 2]) ?></p>
                            <?php if (!empty($product->reviews)): ?>
                                <?php
                                    $count = count($product->reviews);
                                    $avg = round(array_sum(array_column($product->reviews, 'rating')) / $count, 1);
                                ?>
                                <div class="product-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= floor($avg)): ?>
                                            <span class="star filled">&#9733;</span>
                                        <?php elseif ($i - $avg < 1 && $i - $avg > 0): ?>
                                            <span class="star half">&#9733;</span>
                                        <?php else: ?>
                                            <span class="star empty">&#9733;</span>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="rating-text"><?= $avg ?> (<?= $count ?>)</span>
                                </div>
                            <?php else: ?>
                                <div class="product-rating">
                                    <span class="rating-text no-reviews"><?= __('No reviews yet') ?></span>
                                </div>
                            <?php endif; ?>
                            <p class="product-stock"><?= __('In Stock: {0}', $product->stock) ?></p>
                        </div>
                    </a>
                    <div class="product-actions">
                        <?= $this->Html->link(__('View Details'), ['action' => 'view', $product->slug], ['class' => 'btn-view']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
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

