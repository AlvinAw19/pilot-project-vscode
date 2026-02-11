<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 * @var \App\Model\Entity\OrderItem $orderItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('Back to Order'),
                ['controller' => 'Orders', 'action' => 'view', $orderItem->order_id],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="reviews form content">
            <h3><?= __('Leave a Review') ?></h3>
            <p><strong><?= __('Product:') ?></strong> <?= h($orderItem->product->name) ?></p>

            <?= $this->Form->create($review, ['type' => 'file']) ?>
            <fieldset>
                <legend><?= __('Your Review') ?></legend>

                <div class="star-rating-input">
                    <label><?= __('Rating') ?></label>
                    <div class="stars-container">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>"
                                <?= ($review->rating == $i) ? 'checked' : '' ?>>
                            <label for="star<?= $i ?>" title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>">&#9733;</label>
                        <?php endfor; ?>
                    </div>
                    <?php if ($review->getError('rating')): ?>
                        <div class="error-message"><?= implode(', ', $review->getError('rating')) ?></div>
                    <?php endif; ?>
                </div>

                <?= $this->Form->control('comment', [
                    'type' => 'textarea',
                    'label' => __('Comment (optional)'),
                    'rows' => 4,
                    'placeholder' => __('Share your experience with this product...'),
                ]) ?>

                <?= $this->Form->control('image_link', [
                    'type' => 'file',
                    'label' => __('Upload Image (optional)'),
                ]) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit Review')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .star-rating-input {
        margin-bottom: 1.5rem;
    }

    .stars-container {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.25rem;
    }

    .stars-container input[type="radio"] {
        display: none;
    }

    .stars-container label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .stars-container input[type="radio"]:checked ~ label,
    .stars-container label:hover,
    .stars-container label:hover ~ label {
        color: #f5a623;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
</style>
