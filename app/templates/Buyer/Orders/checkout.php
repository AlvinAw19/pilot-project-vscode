<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CartItem[] $cartItems
 * @var float $totalAmount
 */
?>
<div class="orders checkout content">
    <h3><?= __('Checkout') ?></h3>

    <div class="row">
        <div class="column-responsive column-60">
            <h4><?= __('Order Summary') ?></h4>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><?= __('Product') ?></th>
                            <th><?= __('Price') ?></th>
                            <th><?= __('Quantity') ?></th>
                            <th><?= __('Total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?= h($item->product->name) ?></td>
                                <td>$<?= $this->Number->format($item->product->price, ['places' => 2]) ?></td>
                                <td><?= $this->Number->format($item->quantity) ?></td>
                                <td>$<?= $this->Number->format($item->product->price * $item->quantity, ['places' => 2]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong><?= __('Total Amount:') ?></strong></td>
                            <td><strong>$<?= $this->Number->format($totalAmount, ['places' => 2]) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="column-responsive column-40">
            <div style="background: #f8f8f8; padding: 20px; border-radius: 4px;">
                <h4><?= __('Payment Method') ?></h4>
                
                <?= $this->Form->create(null, ['url' => ['action' => 'checkout']]) ?>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #ddd; border-radius: 4px; margin-bottom: 10px; cursor: pointer;">
                        <?= $this->Form->radio('payment_type', [
                            ['value' => 'QR Payment', 'text' => '']
                        ], ['hiddenField' => false]) ?>
                        <div style="margin-left: 10px;">
                            <strong><?= __('QR Payment') ?></strong>
                            <br>
                            <small style="color: #666;"><?= __('Scan QR code to pay') ?></small>
                        </div>
                    </label>

                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #ddd; border-radius: 4px; margin-bottom: 10px; cursor: pointer;">
                        <?= $this->Form->radio('payment_type', [
                            ['value' => 'Credit Card', 'text' => '']
                        ], ['hiddenField' => false]) ?>
                        <div style="margin-left: 10px;">
                            <strong><?= __('Credit Card') ?></strong>
                            <br>
                            <small style="color: #666;"><?= __('Pay with credit/debit card') ?></small>
                        </div>
                    </label>

                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #ddd; border-radius: 4px; margin-bottom: 10px; cursor: pointer;">
                        <?= $this->Form->radio('payment_type', [
                            ['value' => 'Cash on Delivery', 'text' => '']
                        ], ['hiddenField' => false]) ?>
                        <div style="margin-left: 10px;">
                            <strong><?= __('Cash on Delivery') ?></strong>
                            <br>
                            <small style="color: #666;"><?= __('Pay when you receive') ?></small>
                        </div>
                    </label>
                </div>

                <div style="border-top: 2px solid #ddd; padding-top: 15px; margin-top: 15px;">
                    <p style="margin-bottom: 10px;">
                        <strong><?= __('Total to Pay:') ?></strong> 
                        <span style="font-size: 1.5em; color: #28a745;">$<?= $this->Number->format($totalAmount, ['places' => 2]) ?></span>
                    </p>
                    
                    <?= $this->Form->button(__('Complete Order'), [
                        'class' => 'button button-primary',
                        'style' => 'width: 100%; padding: 12px; font-size: 1.1em;'
                    ]) ?>
                    
                    <?= $this->Html->link(__('Back to Cart'), ['controller' => 'Cart', 'action' => 'index'], [
                        'class' => 'button',
                        'style' => 'width: 100%; padding: 12px; margin-top: 10px; text-align: center; display: block;'
                    ]) ?>
                </div>
                
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<style>
input[type="radio"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

label:has(input[type="radio"]:checked) {
    border-color: #1b89bc !important;
    background-color: #e8f4f8 !important;
}

label:hover {
    border-color: #1b89bc;
}
</style>
