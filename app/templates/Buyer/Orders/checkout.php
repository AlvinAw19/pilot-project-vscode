<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CartItem[] $cartItems
 * @var float $totalAmount
 */
?>
<div class="orders form content">
    <h3><?= __('Checkout') ?></h3>

    <div class="table-responsive">
        <h4><?= __('Order Summary') ?></h4>
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
                <td colspan="3"><strong><?= __('Total Amount:') ?></strong></td>
                <td><strong>$<?= $this->Number->format($totalAmount, ['places' => 2]) ?></strong></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <br>
    <h4><?= __('Payment Method') ?></h4>
    <p><strong><?= __('Select Payment Method') ?></strong></p>
    <br>
    <?= $this->Form->create() ?>
    <?= $this->Form->radio(
        'payment_type',
        [
            'QR Payment' => '<small>QR Payment – Scan QR code to pay</small>',
            'Credit Card' => '<small>Credit Card – Pay with credit/debit card</small>',
            'Cash on Delivery' => '<small>Cash on Delivery – Pay when you receive</small>',
        ],
        [
            'escape' => false,
            'separator' => '<br>',
            'required' => true,
        ]
    ) ?>

    <p>
        <strong>
            <?= __('Total to Pay:') ?>
            $<?= $this->Number->format($totalAmount, ['places' => 2]) ?>
        </strong>
    </p>

    <?= $this->Form->button(__('Complete Order')) ?>
    <?= $this->Form->end() ?>

    <div>
        <?= $this->Html->link(
            __('Back to Cart'),
            ['controller' => 'Cart', 'action' => 'index'],
            ['class' => 'button']
        ) ?>
    </div>
</div>
