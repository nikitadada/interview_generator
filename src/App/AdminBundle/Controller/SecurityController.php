<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Form\Security\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    public function loginAction(Request $request)
    {
        /* @var AuthenticationUtils $helper */
        $helper = $this->container->get('security.authentication_utils');

        $error = $helper->getLastAuthenticationError();
        $username = $helper->getLastUsername();

        $data = [
            '_username' => $username,
            '_password' => '',
            '_remember_me' => true,
        ];

        $form = $this
            ->container
            ->getFormFactory()
            ->createNamedBuilder('', LoginType::class, $data, [
                'csrf_protection' => true,
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id' => 'authenticate',
            ])
            ->getForm()
        ;

        return $this->render('@Admin/Security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}