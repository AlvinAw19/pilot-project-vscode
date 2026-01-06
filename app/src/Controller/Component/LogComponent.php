<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * Log component
 *
 * Logs admin user actions to session storage
 */
class LogComponent extends Component
{
    /**
     * beforeFilter callback
     *
     * Called before the controller action
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event The beforeFilter event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        $controller = $this->getController();
        $request = $controller->getRequest();
        $identity = $request->getAttribute('identity');

        // Only log if user is authenticated and is an admin
        if (!$identity || $identity->role !== 'admin') {
            return;
        }

        // Get current logs from session
        $logs = $request->getSession()->read('AdminLogs') ?? [];

        // Append new log entry
        $logs[] = [
            'admin_user_id' => $identity->id,
            'admin_username' => $identity->name,
            'controller' => $request->getParam('controller'),
            'action' => $request->getParam('action'),
            'prefix' => $request->getParam('prefix'),
            'url' => $request->getRequestTarget(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Store back to session
        $request->getSession()->write('AdminLogs', $logs);
    }

    /**
     * Get all admin logs from session
     *
     * @return array<array<string, mixed>>
     */
    public function getLogs(): array
    {
        return $this->getController()->getRequest()->getSession()->read('AdminLogs');
    }
}
