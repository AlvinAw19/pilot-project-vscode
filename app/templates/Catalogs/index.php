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
                'label' => __('Search'),
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

<style>
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-image {
        position: relative;
        background: #f8f9fa;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .product-info {
        padding: 1rem;
    }

    .product-name {
        margin: 0 0 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-price {
        margin: 0 0 0.25rem;
        font-size: 1.25rem;
        font-weight: bold;
        color: #dc5333;
    }

    .product-stock {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    .product-actions {
        padding: 0 1rem 1rem;
    }

    .btn-view {
        display: block;
        text-align: center;
        padding: 0.5rem;
        background: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: background 0.2s;
    }

    .btn-view:hover {
        background: #666;
        color: white;
    }
</style>

