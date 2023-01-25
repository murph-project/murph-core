<?php

namespace App\Core\Controller\Dashboard;

use App\Core\Controller\Admin\AdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardAdminController extends AdminController
{
    protected function getSection(): string
    {
        return 'dashboard';
    }
}
