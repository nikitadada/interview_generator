<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Container\ContainerWrapper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseController extends Controller
{
    /**
     * @var ContainerWrapper
     */
    protected $container;

    /**
     * @var DocumentManager
     */
    protected $dm;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container->get(ContainerWrapper::class);
        $this->dm = $this->container->getDocumentManager();
    }
}