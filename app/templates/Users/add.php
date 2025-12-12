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
        <?= $this->Form->control('name') ?>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
        <?= $this->Form->control('address') ?>
    </fieldset>
    <?= $this->Form->button(__('Register')) ?>
    <?= $this->Form->end() ?>
    <p>Already have an account? <?= $this->Html->link('Login here', ['action' => 'login']) ?></p>
</div>
