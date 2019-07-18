<?php

namespace App\AdminBundle\Twig;

use App\AdminBundle\Container\ContainerWrapper;

class MenuExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerWrapper $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('admin_menu', [$this, 'adminMenu'],  ['is_safe' => ['html']]),
        ];
    }

    public function adminMenu()
    {
        $menu = [
            ['route' => 'admin_dashboard', 'title' =>  'Опросы', 'test' => '/^admin_dashboard/', 'icon' => 'fa fa-bar-chart-o', 'children' => [
                ['route' => 'admin_interview_list', 'title' => 'Список', 'test' =>  '/^admin_interview_list/', 'icon' => 'fa fa-list'],
                ['route' => 'admin_interview_new', 'title' => 'Добавить', 'test' =>  '/^admin_interview_edit/', 'icon' => 'fa fa-user-plus'],
            ]],
            ['route' => 'admin_dashboard', 'title' =>  'Вопросы', 'test' => '/^admin_dashboard/', 'icon' => 'fa fa-bar-chart-o', 'children' => [
                ['route' => 'admin_question_list', 'title' => 'Список', 'test' =>  '/^admin_question_list/', 'icon' => 'fa fa-list'],
                ['route' => 'admin_question_new', 'title' => 'Добавить', 'test' =>  '/^admin_question_new/', 'icon' => 'fa fa-user-plus'],
                ['route' => 'admin_question_tag_list', 'title' => 'Темы вопросов', 'test' =>  '/^admin_question_tag_list/', 'icon' => 'fa fa-list'],
            ]],
            ['route' => 'admin_region_list', 'title' => 'Регионы', 'icon' => 'fa fa-dashboard', 'test' =>  '/^admin_region_list/'],
            ['route' => 'admin_legal_entity_list', 'title' => 'Юридические лица', 'icon' => 'fa fa-dashboard', 'test' =>  '/^admin_legal_entity_list/'],
            ['route' => 'admin_interview_list', 'title' => 'Статистика', 'icon' => 'fa fa-users', 'test' =>  '/^admin_interview_list_/'],
        ];

        return $this->renderMenu($menu, '@Admin/Layout/menu.html.twig');
    }

    protected function renderMenu($menu, $template, $block = 'menu')
    {
        $this->prepareMenu($menu);

        return $this
            ->container
            ->getTwig()
            ->resolveTemplate($template)
            ->renderBlock($block, ['menu' => $menu])
            ;
    }

    protected function prepareMenu(&$menu)
    {
        $request = $this
            ->container
            ->getRequestStack()
            ->getMasterRequest()
        ;
        $router = $this->container->getRouter();
        $route = $request->attributes->get('_route');
        foreach ($menu as &$item) {
            $active = false;
            if (isset($item['children'])) {
                $this->prepareMenu($item['children']);
                foreach ($item['children'] as $child) {
                    if (!empty($child['active'])) {
                        $active = true;
                    }
                }
            }

            if (isset($item['test'])) {
                if (preg_match($item['test'], $route)) {
                    $active = true;
                }
            } else {
                if (isset($item['route']) && $item['route'] == $route) {
                    $active = true;
                }
            }

            $item['active'] = $active;
            if (!isset($item['url'])) {
                if (isset($item['route'])) {
                    $params = isset($item['route_params']) ? $item['route_params'] : [];
                    $item['url'] = $router->generate($item['route'], $params);
                } else {
                    $item['url'] = '#';
                }
            }
        }
        unset($item);
    }

    public function getName()
    {
        return 'menu';
    }
}
