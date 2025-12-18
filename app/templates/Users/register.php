<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\User $roles
 */
?>
<div class="users form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Register here as a Koalala Finds User') ?></legend>
        <?= $this->Form->control('name', ['label' => __('Full Name'), 'required' => true]) ?>
        <?= $this->Form->control('email', ['label' => __('Email'), 'required' => true]) ?>
        <?= $this->Form->control('password', ['label' => __('Password'), 'required' => true]) ?>
        <?= $this->Form->control('address', ['label' => __('Address'), 'required' => true]) ?>
    </fieldset>
    <?= $this->Form->button(__('Register')) ?>
    <?= $this->Form->end() ?>
    <p><?= __('Already have an account?') ?> <?= $this->Html->link(__('Login here'), ['action' => 'login']) ?></p>
</div>
