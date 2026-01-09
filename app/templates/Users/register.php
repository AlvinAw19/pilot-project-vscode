<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\User $roles
 */
?>
<div class="users form">
    <h2><?= __('Koalala Finds') ?></h2>
    <h3><?= __('Create Account') ?></h3>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <div class="input">
            <?= $this->Form->control('name', [
                'label' => __('Full Name'),
                'required' => true,
                'placeholder' => 'John Doe'
            ]) ?>
        </div>
        <div class="input">
            <?= $this->Form->control('email', [
                'label' => __('Email'),
                'required' => true,
                'placeholder' => 'your@email.com'
            ]) ?>
        </div>
        <div class="input">
            <?= $this->Form->control('password', [
                'label' => __('Password'),
                'required' => true,
                'placeholder' => '••••••••'
            ]) ?>
        </div>
        <div class="input">
            <?= $this->Form->control('address', [
                'label' => __('Address'),
                'required' => true,
                'placeholder' => '123 Main Street, City'
            ]) ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Register')) ?>
    <?= $this->Form->end() ?>
    <p><?= __('Already have an account?') ?> <?= $this->Html->link(__('Login here'), ['action' => 'login']) ?></p>
</div>
