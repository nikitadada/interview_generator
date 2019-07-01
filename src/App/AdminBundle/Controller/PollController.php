<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class PollController extends BaseController
{
    public function listAction(Request $request)
    {
        return $this->render('@Admin/Poll/list.html.twig');
    }

    public function editAction(Request $request)
    {
        return $this->render('@Admin/Poll/list.html.twig');
    }

}