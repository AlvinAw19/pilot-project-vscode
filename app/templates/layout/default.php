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

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'styles']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<?php
    $identity = $this->request->getAttribute('identity');
    // Default theme
    $theme = 'background1';
    // Whitelist allowed theme values to avoid unexpected class injection
    $allowedThemes = ['background1', 'background2', 'dark'];
    if ($identity) {
        $candidate = null;
        if (is_object($identity) && isset($identity->theme)) {
            $candidate = (string)$identity->theme;
        } elseif (is_array($identity) && isset($identity['theme'])) {
            $candidate = (string)$identity['theme'];
        }

        if ($candidate !== null) {
            $candidate = trim($candidate);
            if (in_array($candidate, $allowedThemes, true)) {
                $theme = h($candidate);
            }
        }
    }
?>
<body class="theme-<?= $theme ?>">
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>"><span>Koalala</span>Finds</a>
        </div>
        <div class="top-nav-links">
            <?= $this->Navigation->render($identity) ?>
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
<?= $this->Html->script('theme-preview') ?>
</html>
