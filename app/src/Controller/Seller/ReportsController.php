<?php
declare(strict_types=1);

namespace App\Controller\Seller;

use App\Controller\AppController;

/**
 * Reports Controller
 *
 * Placeholder controller for Seller dashboard/reports.
 */
class ReportsController extends AppController
{
    /**
     * Index method - Dashboard placeholder
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

        $title = __('Seller Dashboard');
        $this->set(compact('title'));
    }
}
