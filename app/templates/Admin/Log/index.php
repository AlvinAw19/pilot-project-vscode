<?php
/**
 * @var \App\View\AppView $this
 * @var array $logs
 */
?>
<div class="log index content">
    <h3>Admin Action Logs</h3>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Admin User</th>
            <th>Controller</th>
            <th>Action</th>
            <th>URL</th>
            <th>Timestamp</th>
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
                        <?= h($log['prefix']) ?>/
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

    <p>
        <strong>Note:</strong>
        Logs are stored in session and will be cleared when the session ends.
        Total logs: <?= count($logs) ?>
    </p>
</div>
