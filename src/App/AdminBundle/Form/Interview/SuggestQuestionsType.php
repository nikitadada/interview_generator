<?php

namespace App\AdminBundle\Form\Interview;

use App\AdminBundle\Document\Interview;
use Sirian\SuggestBundle\Form\Type\SuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuggestQuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('questions', SuggestType::class, [
            'required' => false,
            'label' => 'Вопросы',
            'multiple' => true,
            'suggester' => 'question',
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
        ]);
    }

}