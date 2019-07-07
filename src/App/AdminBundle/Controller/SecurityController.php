<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    public function loginAction(AuthenticationUtils $authUtils)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('@Admin/Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function loginCheckAction()
    {

    }

    public function logoutAction()
    {

    }
}