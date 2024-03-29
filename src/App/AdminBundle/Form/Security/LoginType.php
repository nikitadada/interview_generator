<?php

namespace App\AdminBundle\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_username', TextType::class, [
            'label' => 'Email',
        ])->add('_password', PasswordType::class, [
            'label' => 'Пароль',
        ])->add('Login', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);

    }

}
