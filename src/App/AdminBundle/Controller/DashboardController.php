<?php

namespace App\AdminBundle\Controller;

class DashboardController extends BaseController
{
    public function indexAction()
    {
        return $this->render('@Admin/Interview/list.html.twig');
    }
}
