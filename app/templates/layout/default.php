<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>"><span>Cake</span>PHP</a>
        </div>
        <div class="top-nav-links">
            <?php if ($this->request->getAttribute('identity')): ?>
                <?php $user = $this->request->getAttribute('identity'); ?>
                <?php if ($user->role === 'buyer'): ?>
                    <?= $this->Html->link(__('Shop'), ['controller' => 'Catalogs', 'action' => 'index', 'prefix' => false]) ?>
                    <?= $this->Html->link(__('Cart'), ['controller' => 'Cart', 'action' => 'index', 'prefix' => 'Buyer']) ?>
                    <?= $this->Html->link(__('Orders'), ['controller' => 'Orders', 'action' => 'index', 'prefix' => 'Buyer']) ?>
                <?php elseif ($user->role === 'seller'): ?>
                    <?= $this->Html->link(__('Products'), ['controller' => 'Products', 'action' => 'index', 'prefix' => 'Seller']) ?>
                    <?= $this->Html->link(__('Order Items'), ['controller' => 'Orders', 'action' => 'index', 'prefix' => 'Seller']) ?>
                <?php elseif ($user->role === 'admin'): ?>
                    <?= $this->Html->link(__('Users'), ['controller' => 'Users', 'action' => 'index', 'prefix' => 'Admin']) ?>
                    <?= $this->Html->link(__('Categories'), ['controller' => 'Categories', 'action' => 'index', 'prefix' => 'Admin']) ?>
                    <?= $this->Html->link(__('Products'), ['controller' => 'Products', 'action' => 'index', 'prefix' => 'Admin']) ?>
                    <?= $this->Html->link(__('Orders'), ['controller' => 'Orders', 'action' => 'index', 'prefix' => 'Admin']) ?>
                <?php endif; ?>
                <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout', 'prefix' => false]) ?>
            <?php else: ?>
                <?= $this->Html->link(__('Login'), ['controller' => 'Users', 'action' => 'login', 'prefix' => false]) ?>
                <?= $this->Html->link(__('Register'), ['controller' => 'Users', 'action' => 'register', 'prefix' => false]) ?>
            <?php endif; ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
