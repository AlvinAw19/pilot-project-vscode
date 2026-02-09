<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Form\ResetPasswordForm $form
 * @var string $token
 */
?>
<div class="users form content">
    <h1>Reset Password</h1>
    <p>Enter your new password below.</p>
    <?= $this->Form->create($form) ?>
    <fieldset>
        <?= $this->Form->control('password', ['label' => 'New Password', 'type' => 'password']) ?>
        <?= $this->Form->control('confirm_password', ['label' => 'Confirm Password', 'type' => 'password']) ?>
    </fieldset>
    <?= $this->Form->button(__('Reset Password')) ?>
    <?= $this->Form->end() ?>
</div>
