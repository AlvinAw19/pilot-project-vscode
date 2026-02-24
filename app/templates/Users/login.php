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
        <?= $this->Html->link(__('Forgot password?'), ['action' => 'forgotPassword']) ?>
    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
    <div class="register-row">
        <span class="register-text"><?= __('Don\'t have an account?') ?></span>
        <?= $this->Html->link(__('Register here'), ['action' => 'register'], ['class' => 'button button-outline auth-action']) ?>
    </div>

    <div class="auth-or"><?= __('or') ?></div>

    <div class="google-row">
        <?php
        $googleSvg = '<svg viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="20" height="20">'
            . '<path fill="#4285f4" d="M533.5 278.4c0-18.5-1.6-36.2-4.6-53.4H272v100.9h147.2c-6.3 34-25.3 62.8-54.2 82v67h87.6c51.2-47.1 80.9-116.6 80.9-196.5z"/>'
            . '<path fill="#34a853" d="M272 544.3c73.6 0 135.4-24.4 180.6-66.4l-87.6-67c-24.4 16.4-55.4 26-93 26-71.4 0-132-48.2-153.5-113.1H30.4v70.9C75.6 494.9 167.3 544.3 272 544.3z"/>'
            . '<path fill="#fbbc04" d="M118.5 325.4c-10.9-32.9-10.9-68.3 0-101.2V153.3H30.4c-38.4 76.9-38.4 168.2 0 245.1l88.1-73z"/>'
            . '<path fill="#ea4335" d="M272 107.6c39.9 0 75.8 13.7 104.1 40.6l78-78C405.6 24.4 343.8 0 272 0 167.3 0 75.6 49.4 30.4 153.3l88.1 70.9C140 155.8 200.6 107.6 272 107.6z"/>'
            . '</svg>';

        echo $this->Html->link($googleSvg . ' ' . __('Continue with Google'), ['action' => 'googleLogin'], ['class' => 'google-btn auth-action', 'escape' => false]);
        ?>
    </div>
</div>
