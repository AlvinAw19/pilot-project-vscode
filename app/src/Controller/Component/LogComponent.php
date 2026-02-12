<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\User;
use Cake\Controller\Component;

/**
 * Log component
 *
 * Logs admin user actions to database
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

        $logTable = $controller->fetchTable('Logs');
        $log = $logTable->newEntity([
            'user_id' => $identity->id,
            'url' => $request->getRequestTarget(),
            'ip_address' => $request->clientIp(),
        ]);

        $logTable->save($log);
    }

    /**
     * Get all logs from database
     *
     * @return \Cake\ORM\Query
     */
    public function getLogs()
    {
        return $this->getController()
            ->fetchTable('Logs')
            ->find()
            ->contain(['Users'])
            ->orderDesc('Logs.created');
    }
}
