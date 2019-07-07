<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FilterType extends AbstractType
{
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['uniqid'] = uniqid('filter_form_');
    }
}
