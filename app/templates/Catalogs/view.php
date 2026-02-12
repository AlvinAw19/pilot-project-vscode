<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var \Cake\ORM\ResultSet $reviews
 * @var float|null $avgRating
 * @var int $reviewCount
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
                            ['width' => 200]
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

            <!-- Reviews Section -->
            <div class="reviews-section">
                <h4><?= __('Customer Reviews') ?></h4>

                <?php if ($avgRating !== null): ?>
                    <div class="rating-summary">
                        <div class="avg-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($avgRating)): ?>
                                    <span class="star filled">&#9733;</span>
                                <?php elseif ($i - $avgRating < 1 && $i - $avgRating > 0): ?>
                                    <span class="star half">&#9733;</span>
                                <?php else: ?>
                                    <span class="star empty">&#9733;</span>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="avg-text"><?= $avgRating ?> <?= __('out of 5') ?></span>
                        </div>
                        <p class="review-count"><?= __('Based on {0} review(s)', $reviewCount) ?></p>
                    </div>
                <?php else: ?>
                    <p class="no-reviews"><?= __('No reviews yet. Be the first to review this product!') ?></p>
                <?php endif; ?>

                <?php if ($reviews->count() > 0): ?>
                    <div class="reviews-list">
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="review-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?= $i <= $review->rating ? 'filled' : 'empty' ?>">&#9733;</span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="review-author"><?= h($review->user->name) ?></span>
                                    <span class="review-date"><?= h($review->created->format('M d, Y')) ?></span>
                                </div>
                                <?php if ($review->comment): ?>
                                    <p class="review-comment"><?= h($review->comment) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($review->image_link)): ?>
                                    <div class="review-images">
                                        <?= $this->Html->image($review->image_link, [
                                            'alt' => __('Review image by {0}', h($review->user->name)),
                                            'width' => 200,
                                            'style' => 'border-radius: 8px;',
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .reviews-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }

    .rating-summary {
        margin-bottom: 1.5rem;
    }

    .avg-stars {
        display: flex;
        align-items: center;
        gap: 0.15rem;
    }

    .avg-stars .star {
        font-size: 1.5rem;
    }

    .avg-stars .avg-text {
        font-size: 1rem;
        color: #333;
        margin-left: 0.5rem;
        font-weight: 600;
    }

    .review-count {
        color: #666;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .no-reviews {
        color: #999;
        font-style: italic;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .review-card {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #eee;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .review-stars .star {
        font-size: 1rem;
    }

    .star.filled {
        color: #f5a623;
    }

    .star.half {
        color: #f5a623;
        opacity: 0.6;
    }

    .star.empty {
        color: #ddd;
    }

    .review-author {
        font-weight: 600;
        color: #333;
    }

    .review-date {
        color: #999;
        font-size: 0.85rem;
    }

    .review-comment {
        margin: 0.5rem 0;
        color: #444;
        line-height: 1.5;
    }

    .review-images {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
</style>

