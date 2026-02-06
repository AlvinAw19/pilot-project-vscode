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
                            <?php if ($product->stock <= 5 && $product->stock > 0): ?>
                                <span class="stock-badge low"><?= __('Selling fast') ?></span>
                            <?php elseif ($product->stock == 0): ?>
                                <span class="stock-badge out"><?= __('Out of Stock') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h4 class="product-name"><?= h($product->name) ?></h4>
                            <p class="product-price">$<?= $this->Number->format($product->price, ['places' => 2]) ?></p>
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
        <div class="no-products">
            <p>😕 <?= __('No products found.') ?></p>
            <?php if (!empty($searchTerm) || $categoryId): ?>
                <p><?= $this->Html->link(__('Browse all products'), ['action' => 'index']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .catalog-header h3 {
        margin: 0;
        font-size: 1.75rem;
    }

    .catalog-actions {
        display: flex;
        gap: 0.75rem;
    }

    .catalog-actions .button {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .catalog-filters {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .search-form {
        margin-bottom: 1rem;
    }

    .search-input-wrapper {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 0.6rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .search-btn {
        padding: 0.6rem 1.25rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .search-btn:hover {
        background: #0056b3;
    }

    .clear-btn {
        padding: 0.6rem 1rem;
        background: #6c757d;
        color: white;
        border-radius: 6px;
        text-decoration: none;
    }

    .category-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .pill {
        padding: 0.4rem 1rem;
        background: white;
        border: 1px solid #ddd;
        border-radius: 20px;
        text-decoration: none;
        color: #333;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .pill:hover {
        background: #e9ecef;
    }

    .pill.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .active-filters {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .filter-tag {
        background: #e7f3ff;
        color: #0056b3;
        padding: 0.3rem 0.75rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }

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

    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .stock-badge.low {
        background: #fff3cd;
        color: #856404;
    }

    .stock-badge.out {
        background: #f8d7da;
        color: #721c24;
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
        color: #28a745;
    }

    .product-stock {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    .product-stock.low {
        color: #dc3545;
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
        background: #0056b3;
        color: white;
    }

    .no-products {
        text-align: center;
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .no-products p {
        margin: 0.5rem 0;
        color: #666;
    }

    .no-products a {
        color: #007bff;
    }

    @media (max-width: 768px) {
        .catalog-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .product-image {
            height: 150px;
        }
    }
</style>

