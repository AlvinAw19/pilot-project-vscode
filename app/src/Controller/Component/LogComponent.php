<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\User;
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

        if (!$identity || $identity->role !== User::ROLE_ADMIN) {
            return;
        }

        $logs = $request->getSession()->read('AdminLogs') ?? [];

        $logs[] = [
            'admin_user_id' => $identity->id,
            'admin_username' => $identity->name,
            'controller' => $request->getParam('controller'),
            'action' => $request->getParam('action'),
            'prefix' => $request->getParam('prefix'),
            'url' => $request->getRequestTarget(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

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
