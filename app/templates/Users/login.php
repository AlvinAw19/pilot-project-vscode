<?php
/** @var \App\View\AppView $this */
?>
<div class="users form">
    <?= $this->Flash->render() ?>
    <h2><?= __('Koalala Finds') ?></h2>
    <h3><?= __('Login') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <div class="input">
            <?= $this->Form->control('email', [
                'required' => true,
                'label' => __('Email'),
                'placeholder' => 'your@email.com'
            ]) ?>
        </div>
        <div class="input">
            <?= $this->Form->control('password', [
                'required' => true,
                'label' => __('Password'),
                'placeholder' => '••••••••'
            ]) ?>
        </div>
    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
    <p><?= __('Don\'t have an account?') ?> <?= $this->Html->link(__('Register here'), ['action' => 'register']) ?></p>
</div>
