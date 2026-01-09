<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Log Controller
 *
 * Admin action log reporting
 *
 * @property \App\Controller\Component\LogComponent $Log
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
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
        if ($identity === null) {
            throw new \Authorization\Exception\ForbiddenException();
        }
        $user = $identity->getOriginalData();
        $this->Authorization->authorize($user, 'viewLogs');

        $logs = $this->Log->getLogs();
        $logs = array_reverse($logs);

        $this->set(compact('logs'));
    }
}
