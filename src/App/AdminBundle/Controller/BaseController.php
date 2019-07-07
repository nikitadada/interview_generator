<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Container\ContainerWrapper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;

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

    protected function isValidForm(FormInterface $form)
    {
        return $form->isSubmitted() && $form->isValid();
    }

    public function createFilterForm($type, $data = null, array $options = [])
    {
        $options = array_merge(['csrf_protection' => false, 'allow_extra_fields' => true], $options);

        $builder = $this
            ->container
            ->getFormFactory()
            ->createNamedBuilder('', $type, $data, $options);

        $builder->setMethod('GET');

        return $builder->getForm();
    }

    /**
     * @param $target
     * @param int $limit
     * @param array $options
     * @return SlidingPagination
     */
    protected function paginate($target, $limit = 50, array $options = [])
    {
        $options = array_merge([
            'defaultSortFieldName' => '_id',
            'defaultSortDirection' => 'DESC',
            'defaultDirection' => 'DESC'
        ], $options);

        $request = $this->container->getRequestStack()->getCurrentRequest();
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', $limit);

        return $this->container->get('knp_paginator')->paginate($target, $page, $limit, $options);
    }
}