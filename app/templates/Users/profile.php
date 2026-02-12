<?php
/**
 * the User profile template is generated using copilot.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var bool $hasPassword
 */
?>
<div class="row">
    <div class="column">
        <div class="users form content">
            <h2><?= __('My Profile') ?></h2>

            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Profile Information') ?></legend>

                <?= $this->Form->control('name', [
                    'label' => __('Name'),
                    'required' => true,
                ]) ?>

                <?= $this->Form->control('email', [
                    'label' => __('Email'),
                    'readonly' => true,
                ]) ?>
                <?= $this->Form->control('address', [
                    'label' => __('Address'),
                    'type' => 'textarea',
                    'rows' => 3,
                ]) ?>
            </fieldset>

            <fieldset>
                <legend><?= $hasPassword ? __('Change Password') : __('Set Password') ?></legend>

                <?php if (!$hasPassword): ?>
                    <p class="message">
                        <?= __('You signed up using Google. Set a password below to enable email/password login.') ?>
                    </p>
                <?php else: ?>
                    <p class="help-text"><?= __('Only fill this section if you want to change your password.') ?></p>
                <?php endif; ?>

                <?= $this->Form->control('new_password', [
                    'label' => $hasPassword ? __('New Password') : __('Password'),
                    'type' => 'password',
                    'required' => false,
                    'value' => '',
                ]) ?>

                <?= $this->Form->control('confirm_password', [
                    'label' => __('Confirm Password'),
                    'type' => 'password',
                    'required' => false,
                    'value' => '',
                ]) ?>
            </fieldset>

            <div class="user-meta">
                <p><strong><?= __('Member Since') ?>:</strong> <?= h($user->created->format('F j, Y')) ?></p>
            </div>

            <?= $this->Form->button(__('Update Profile')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
