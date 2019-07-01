<?php

namespace App\AdminBundle\Container;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerWrapper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    public function get($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return $this->container->get($id, $invalidBehavior);
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function getMongo()
    {
        return $this->get('doctrine_mongodb');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->getMongo()->getManager();
    }

    public function getRouter()
    {
        return $this->get('router');
    }

    public function getTemplating()
    {
        return $this->container->get('templating');
    }

    public function getFormFactory()
    {
        return $this->container->get('form.factory');
    }

    public function getTwig()
    {
        return $this->container->get('twig');
    }

    public function getAuthorizationChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    public function getTokenStorage()
    {
        return $this->container->get('security.token_storage');
    }


    public function getRequestStack()
    {
        return $this->container->get('request_stack');
    }

}
