<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $logs
 */
?>
<div class="log index content">
    <h3>Admin Action Logs</h3>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>URL</th>
            <th>IP Address</th>
            <th>Timestamp</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($logs as $index => $log): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td>
                    <?= h($log->user->name) ?>
                    <small>(ID: <?= h($log->user->id) ?>)</small>
                </td>
                <td><code><?= h($log->url) ?></code></td>
                <td><?= h($log->ip_address) ?></td>
                <td><?= h($log->created) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>

    <p>
        <strong>Note:</strong>
        Logs are stored in the database persistently.
    </p>
</div>
