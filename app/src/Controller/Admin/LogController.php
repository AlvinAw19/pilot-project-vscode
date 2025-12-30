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
        // Skip authorization check for now (or use policy if needed)
        $this->Authorization->skipAuthorization();

        // Get logs from session via LogComponent
        $logs = $this->Log->getLogs();

        // Reverse to show newest first
        $logs = array_reverse($logs);

        $this->set(compact('logs'));
    }
}
