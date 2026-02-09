<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Log Controller
 *
 * Admin action log reporting
 *
 * @property \App\Model\Table\LogsTable $Logs
 * @property \App\Controller\Component\LogComponent $Log
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class LogsController extends AppController
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
        $log = $this->Logs->newEmptyEntity();
        $this->Authorization->authorize($log);

        $logs = $this->paginate($this->Log->getLogs());

        $this->set(compact('logs'));
    }
}
