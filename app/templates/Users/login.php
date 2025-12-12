<?php
/** @var \App\View\AppView $this */
?>
<div class="users form">
    <?= $this->Flash->render() ?>
    <h2>Welcome to Koalala Finds 🐨🔍</h2>
    <h3>Login</h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('email', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true]) ?>
    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
    <p>Don't have an account? <?= $this->Html->link('Register here', ['action' => 'add']) ?></p>
</div>
