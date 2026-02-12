<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CartItem> $cartItems
 * @var float $totalPrice
 */
?>

<div class="cart index content">
    <h3><?= __('Shopping Cart') ?></h3>

    <?php if (!$cartItems->isEmpty()): ?>
        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th><?= __('Product') ?></th>
                    <th><?= __('Price') ?></th>
                    <th><?= __('Quantity') ?></th>
                    <th><?= __('Subtotal') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <?= $this->Html->link(
                                h($item->product->name),
                                ['prefix'=>false, 'controller' => 'Catalogs', 'action' => 'view', $item->product->slug],
                            ) ?>
                        </td>

                        <td>
                            <?= $this->Number->currency($item->product->price) ?>
                        </td>

                        <td>
                            <?= $this->Form->create($item, [
                                'url' => ['action' => 'update', $item->id],
                                'class' => 'inline-form'
                            ]) ?>

                            <?= $this->Form->control('quantity', [
                                'type' => 'number',
                                'min' => 1,
                                'max' => $item->product->stock,
                                'label' => false
                            ]) ?>

                            <small>
                                <?= __('Available: {0}', $item->product->stock) ?>
                            </small>
                        </td>

                        <td>
                            <?= $this->Number->currency($item->product->price * $item->quantity) ?>
                        </td>

                        <td class="actions">
                            <?= $this->Form->button(__('Update')) ?>
                            <?= $this->Form->end() ?>
                            <?= $this->Form->postLink(
                                __('Remove'),
                                ['action' => 'delete', $item->id],
                                [
                                    'confirm' => __(
                                        'Are you sure you want to remove {0} from your cart?',
                                        $item->product->name
                                    )
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="3"><strong><?= __('Total') ?></strong></td>
                    <td colspan="2">
                        <strong><?= $this->Number->currency($totalPrice) ?></strong>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <br>
        <div class="cart-actions">
            <?= $this->Html->link(__('Continue Shopping'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
            <?= $this->Html->link(__('Proceed to Checkout'), ['controller' => 'CartItems', 'action' => 'checkout'], ['class' => 'button']) ?>
        </div>

    <?php else: ?>
        <div class="message">
            <p><?= __('Your cart is empty.') ?></p>
            <br>
            <p>
                <?= $this->Html->link(__('Browse Products'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
            </p>
        </div>
    <?php endif; ?>
</div>
