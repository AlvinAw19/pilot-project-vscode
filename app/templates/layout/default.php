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

$cakeDescription = 'Koalala Finds';
$userName = $this->request->getAttribute('identity');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>
        <?php if ($this->fetch('title')): ?>
            - <?= $this->fetch('title') ?>
        <?php endif; ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'styles']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>">
                <span>Koalala</span> Finds
            </a>
        </div>
        <div class="top-nav-links">
            <?php if ($userName): ?>
                <a href="<?= $this->Url->build(['controller' => 'Catalogs', 'action' => 'index']) ?>">
                    Catalog
                </a>
                <?php if ($userName->role === 'admin'): ?>
                    <a href="<?= $this->Url->build(['prefix' => 'Admin', 'controller' => 'Products', 'action' => 'index']) ?>">
                        Admin Panel
                    </a>
                <?php elseif ($userName->role === 'seller'): ?>
                    <a href="<?= $this->Url->build(['prefix' => 'Seller', 'controller' => 'Products', 'action' => 'index']) ?>">
                        My Products
                    </a>
                <?php endif; ?>
                <?php if ($userName->role === 'buyer' || $userName->role === 'admin'): ?>
                    <a href="<?= $this->Url->build(['prefix' => 'Buyer', 'controller' => 'CartItems', 'action' => 'index']) ?>">
                        Cart
                    </a>
                    <a href="<?= $this->Url->build(['prefix' => 'Buyer', 'controller' => 'Orders', 'action' => 'index']) ?>">
                        Orders
                    </a>
                <?php endif; ?>
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>">
                    Logout (<?= h($userName->name) ?>)
                </a>
            <?php else: ?>
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>">
                    Login
                </a>
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']) ?>">
                    Register
                </a>
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
        <p>&copy; <?= date('Y') ?> Koalala Finds. All rights reserved.</p>
    </footer>
</body>
</html>
