<?php
/** @var \App\View\AppView $this */
?>
<div class="users form">
    <?= $this->Flash->render() ?>
    <h2><?= __('Welcome to Koalala Finds') ?></h2>
    <h3><?= __('Login') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('email', ['required' => true, 'label' => __('Email')]) ?>
        <?= $this->Form->control('password', ['required' => true, 'label' => __('Password')]) ?>
    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
    <p><?= __('Don\'t have an account?') ?> <?= $this->Html->link(__('Register here'), ['action' => 'register']) ?></p>
</div>
