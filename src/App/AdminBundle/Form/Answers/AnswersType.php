<?php

namespace App\AdminBundle\Form\Answers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AnswersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('answers', TextType::class, [
            'required' => true,
            'label' => 'Введите текст варианта ответа',
            'mapped' => false,
        ]);
    }

}