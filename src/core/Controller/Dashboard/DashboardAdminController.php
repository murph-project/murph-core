<?php

namespace App\Core\Controller\Dashboard;

use App\Core\Controller\Admin\AdminController;

class DashboardAdminController extends AdminController
{
    protected function getSection(): string
    {
        return 'dashboard';
    }
}
