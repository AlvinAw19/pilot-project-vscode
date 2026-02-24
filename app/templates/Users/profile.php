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
                    <div class="theme-previews">
                        <?php
                        $themeOptions = [
                            'background1' => ['label' => __('CakePHP'), 'img' => $this->Url->build('/img/themes/background1.jpg')],
                            'background2' => ['label' => __('KoalalaFinds'), 'img' => $this->Url->build('/img/themes/background2.jpg')],
                            'dark' => ['label' => __('Dark Mode'), 'img' => $this->Url->build('/img/themes/dark-preview.svg')],
                        ];
                        foreach ($themeOptions as $key => $meta):
                            $isSelected = isset($user->theme) && $user->theme === $key;
                        ?>
                            <label class="theme-option<?= $isSelected ? ' selected' : '' ?>">
                                <input type="radio" name="theme" value="<?= h($key) ?>" <?= $isSelected ? 'checked' : '' ?> />
                                <img src="<?= h($meta['img']) ?>" alt="<?= h($meta['label']) ?>" />
                                <span class="theme-label"><?= h($meta['label']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
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

            <div class="profile-actions">
                <?= $this->Form->button(__('Update Profile')) ?>
                <?= $this->Html->link(__('Back to Catalog'), ['controller' => 'Catalogs', 'action' => 'index'], ['class' => 'button', 'escape' => false]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
