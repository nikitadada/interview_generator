<?php

namespace App\AdminBundle\Form\Interview;

use App\AdminBundle\Filter\InterviewFilter;
use App\AdminBundle\Form\FilterType;
use App\AdminBundle\Form\DateRangeType;
use Sirian\SuggestBundle\Form\Type\SuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRange', DateRangeType::class, [
                'label' => 'Дата создания',
            ])
            ->add('title', SuggestType::class, [
                'required' => false,
                'label' => 'Название',
                'suggester' => 'interview',
            ])->add('regions', SuggestType::class, [
                'required' => false,
                'label' => 'Регионы',
                'multiple' => true,
                'suggester' => 'regions',
            ])->add('legalEntities', SuggestType::class, [
                'required' => false,
                'label' => 'Юридические лица',
                'multiple' => true,
                'suggester' => 'legal_entities',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', InterviewFilter::class);
    }

    public function getParent()
    {
        return FilterType::class;
    }
}
