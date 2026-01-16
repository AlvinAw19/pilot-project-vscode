<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Form\ForgotPasswordForm $form
 */
?>
<div class="users form content">
    <h1>Forgot Password</h1>
    <p>Enter your email address and we'll send you a link to reset your password.</p>
    <?= $this->Form->create($form) ?>
    <fieldset>
        <legend>Reset Password</legend>
        <?= $this->Form->control('email', ['label' => 'Email']) ?>
    </fieldset>
    <?= $this->Form->button(__('Send Reset Link')) ?>
    <?= $this->Form->end() ?>
    <br>
    <?= $this->Html->link('Back to Login', ['action' => 'login']) ?>
</div>