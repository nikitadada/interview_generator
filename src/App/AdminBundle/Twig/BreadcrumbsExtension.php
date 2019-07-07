<?php

namespace App\AdminBundle\Twig;

class BreadcrumbsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    public function getGlobals()
    {
        return [
            'breadcrumbs' => $this->breadcrumbs,
        ];
    }

    public function getName()
    {
        return 'breadcrumbs';
    }
}
