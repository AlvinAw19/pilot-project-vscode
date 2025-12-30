<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Back to Catalog'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?php if ($product->has('category')): ?>
                <?= $this->Html->link(__('View Category'), ['action' => 'index', '?' => ['category_id' => $product->category->id]], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="products view content">
            <h3><?= h($product->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= $product->has('category') ? h($product->category->name) : '' ?></td>
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
                        <?php if (!empty($product->image_link)): ?>
                            <?= $this->Html->image($product->image_link, ['width' => 300]) ?>
                        <?php else: ?>
                            <em><?= __('No image available') ?></em>
                        <?php endif; ?>
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
            <br>
            <?php if ($product->stock > 0): ?>
                <?php
                $identity = $this->request->getAttribute('identity');
                if ($identity && $identity->role === 'buyer'):
                ?>
                    <?= $this->Form->create(null, [
                        'url' => ['controller' => 'Cart', 'action' => 'add', $product->id, 'prefix' => 'Buyer'],
                        'type' => 'post'
                    ]) ?>
                    <div style="margin-bottom: 15px;">
                        <?= $this->Form->control('quantity', [
                            'type' => 'number',
                            'label' => __('Quantity:'),
                            'value' => 1,
                            'min' => 1,
                            'max' => $product->stock,
                            'style' => 'width: 100px;',
                            'required' => true
                        ]) ?>
                    </div>
                    <?= $this->Form->button(__('Add to Cart'), [
                        'class' => 'button',
                        'style' => 'background-color: #dc3545;'
                    ]) ?>
                    <?= $this->Form->end() ?>
                <?php else: ?>
                    <?= $this->Html->link(
                        __('Login to Add to Cart'),
                        ['controller' => 'Users', 'action' => 'login', 'prefix' => false],
                        ['class' => 'button', 'style' => 'background-color: #dc3545;']
                    ) ?>
                <?php endif; ?>
            <?php else: ?>
                <div style="padding: 20px; background: #fee; border-radius: 4px;">
                    <strong style="color: #c00;"><?= __('Out of Stock') ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

