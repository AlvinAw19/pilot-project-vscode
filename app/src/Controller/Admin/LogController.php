<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Authorization\Exception\ForbiddenException;

/**
 * Log Controller
 *
 * Admin action log reporting
 *
 * @property \App\Controller\Component\LogComponent $Log
 * @property \App\Model\Table\UsersTable $Users
 */
class LogController extends AppController
{
    /**
     * Index method
     *
     * Display all admin action logs from session
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            throw new ForbiddenException('Not authenticated');
        }
        /** @var \App\Model\Entity\User $user */
        $user = $identity->getOriginalData();
        $this->Authorization->authorize($user, 'viewLogs');

        // Get logs from session via LogComponent
        $logs = $this->Log->getLogs();

        // Reverse to show newest first
        $logs = array_reverse($logs);

        $this->set(compact('logs'));
    }
}
