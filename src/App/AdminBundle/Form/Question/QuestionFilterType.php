<?php

namespace App\AdminBundle\Form\Question;

use App\AdminBundle\Form\FilterType;
use App\AdminBundle\Filter\QuestionFilter;
use App\AdminBundle\Form\DateRangeType;
use Sirian\SuggestBundle\Form\Type\SuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRange', DateRangeType::class)
            ->add('title', SuggestType::class, [
                'required' => false,
                'suggester' => 'question'
            ])->add('questionTag', SuggestType::class, [
                'required' => false,
                'suggester' => 'question_tag'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', QuestionFilter::class);
    }

    public function getParent()
    {
        return FilterType::class;
    }
}
