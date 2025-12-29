<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CartItem> $cartItems
 * @var float $totalPrice
 */
?>
<div class="cart index content">
    <h3><?= __('Shopping Cart') ?></h3>
    
    <?php if ($cartItems->count() > 0): ?>
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
                                    ['controller' => 'Catalogs', 'action' => 'view', $item->product->slug],
                                    ['prefix' => false]
                                ) ?>
                            </td>
                            <td>$<?= $this->Number->format($item->product->price, ['places' => 2]) ?></td>
                            <td>
                                <?= $this->Form->control('quantity-' . $item->id, [
                                    'type' => 'number',
                                    'value' => $item->quantity,
                                    'min' => 1,
                                    'max' => $item->product->stock,
                                    'label' => false,
                                    'style' => 'width: 80px;',
                                    'id' => 'quantity-' . $item->id
                                ]) ?>
                                <small><?= __('Available: {0}', $item->product->stock) ?></small>
                            </td>
                            <td>$<?= $this->Number->format($item->product->price * $item->quantity, ['places' => 2]) ?></td>
                            <td class="actions">
                                <?= $this->Form->create(null, [
                                    'url' => ['action' => 'update', $item->id],
                                    'type' => 'post',
                                    'style' => 'display: inline-block; margin-right: 10px;',
                                    'id' => 'update-form-' . $item->id
                                ]) ?>
                                <?= $this->Form->hidden('quantity', ['id' => 'hidden-quantity-' . $item->id]) ?>
                                <a href="#" onclick="document.getElementById('hidden-quantity-<?= $item->id ?>').value = document.getElementById('quantity-<?= $item->id ?>').value; document.getElementById('update-form-<?= $item->id ?>').submit(); return false;">
                                    <?= __('Update') ?>
                                </a>
                                <?= $this->Form->end() ?>
                                <?= $this->Form->postLink(
                                    __('Remove'),
                                    ['action' => 'delete', $item->id],
                                    ['confirm' => __('Are you sure you want to remove {0} from cart?', $item->product->name)]
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong><?= __('Total:') ?></strong></td>
                        <td colspan="2"><strong>$<?= $this->Number->format($totalPrice, ['places' => 2]) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            <?= $this->Html->link(__('Continue Shopping'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
            <span style="margin: 0 10px;">|</span>
            <?= $this->Html->link(__('Proceed to Checkout'), '#', ['class' => 'button', 'disabled' => true, 'style' => 'background-color: #ccc;']) ?>
            <small style="display: block; margin-top: 10px; color: #666;">
                <em><?= __('Note: Checkout functionality will be implemented in a future update.') ?></em>
            </small>
        </div>
    <?php else: ?>
        <div class="message" style="padding: 40px; text-align: center; background: #f8f8f8; border-radius: 4px;">
            <p><?= __('Your cart is empty.') ?></p>
            <p>
                <?= $this->Html->link(__('Browse Products'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false], ['class' => 'button']) ?>
            </p>
        </div>
    <?php endif; ?>
</div>
