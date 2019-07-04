<?php

namespace App\AdminBundle\Security;

use App\AdminBundle\Container\ContainerWrapper;
use App\AdminBundle\Document\User;

class SecurityHelper
{
    /**
     * @var ContainerWrapper
     */
    protected $container;

    public function __construct(ContainerWrapper $container)
    {
        $this->container = $container;
    }

    public function getImpersonateLink(User $user)
    {
        return sprintf('//%s.%s', $user->getId(), $this->container->getParameter('impersonate_host'));
    }
}
