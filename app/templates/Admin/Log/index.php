<?php
/**
 * @var \App\View\AppView $this
 * @var array $logs
 */
?>
<div class="log index content">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3><?= __('Admin Action Logs') ?></h3>
    </div>

    <?php if (!empty($logs)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= __('#') ?></th>
                        <th><?= __('Admin User') ?></th>
                        <th><?= __('Controller') ?></th>
                        <th><?= __('Action') ?></th>
                        <th><?= __('URL') ?></th>
                        <th><?= __('Timestamp') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $index => $log): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <?= h($log['admin_username']) ?>
                                <small>(ID: <?= h($log['admin_user_id']) ?>)</small>
                            </td>
                            <td>
                                <?php if (!empty($log['prefix'])): ?>
                                    <span style="color: #666;"><?= h($log['prefix']) ?>/</span>
                                <?php endif; ?>
                                <?= h($log['controller']) ?>
                            </td>
                            <td><?= h($log['action']) ?></td>
                            <td><code><?= h($log['url']) ?></code></td>
                            <td><?= h($log['timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="message" style="margin-top: 1rem; padding: 1rem; background-color: #e7f3ff; border-left: 4px solid #2196F3;">
            <p style="margin: 0;">
                <strong><?= __('Note:') ?></strong>
                <?= __('Logs are stored in session and will be cleared when the session ends.') ?>
                <?= __('Total logs: {0}', count($logs)) ?>
            </p>
        </div>
    <?php else: ?>
        <div class="message">
            <p><?= __('No admin action logs found.') ?></p>
            <p><?= __('Admin actions will be automatically logged and displayed here.') ?></p>
        </div>
    <?php endif; ?>
</div>
