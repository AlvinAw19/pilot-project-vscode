<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Log component
 *
 * Logs admin user actions to session storage
 */
class LogComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the component.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * beforeFilter callback
     *
     * Called before the controller action
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        $this->logAction();
    }

    /**
     * Log admin action to session
     *
     * Records controller name, action name, full URL, and timestamp
     * for admin users only.
     *
     * @return void
     */
    public function logAction(): void
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

        // Create new log entry
        $logEntry = [
            'admin_user_id' => $identity->id,
            'admin_username' => $identity->username,
            'controller' => $request->getParam('controller'),
            'action' => $request->getParam('action'),
            'prefix' => $request->getParam('prefix'),
            'url' => $request->getRequestTarget(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Append new entry
        $logs[] = $logEntry;

        // Store back to session
        $request->getSession()->write('AdminLogs', $logs);
    }

    /**
     * Get all admin logs from session
     *
     * @return array
     */
    public function getLogs(): array
    {
        $controller = $this->getController();
        $request = $controller->getRequest();
        
        return $request->getSession()->read('AdminLogs') ?? [];
    }
}
