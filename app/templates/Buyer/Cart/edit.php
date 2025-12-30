<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CartItem $cartItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Back to Cart'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(
                __('Remove Item'),
                ['action' => 'delete', $cartItem->id],
                ['confirm' => __('Are you sure you want to remove this item from cart?'), 'class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="cart edit content">
            <h3><?= __('Edit Cart Item') ?></h3>
            <?= $this->Form->create($cartItem) ?>
            <fieldset>
                <legend><?= __('Update Quantity') ?></legend>
                <div>
                    <strong><?= __('Product:') ?></strong> <?= h($cartItem->product->name) ?>
                </div>
                <div>
                    <strong><?= __('Price:') ?></strong> $<?= $this->Number->format($cartItem->product->price, ['places' => 2]) ?>
                </div>
                <div>
                    <strong><?= __('Available Stock:') ?></strong> <?= $cartItem->product->stock ?> units
                </div>
                <br>
                <?= $this->Form->control('quantity', [
                    'type' => 'number',
                    'min' => 1,
                    'max' => $cartItem->product->stock,
                    'label' => __('Quantity')
                ]) ?>
            </fieldset>
            <?= $this->Form->button(__('Update Cart'), ['class' => 'button']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
