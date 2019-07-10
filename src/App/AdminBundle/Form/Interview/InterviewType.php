<?php

namespace App\AdminBundle\Form\Interview;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Document\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dm = $options['documentManager'];

        $regions = $dm->getRepository(Region::class)->findAll();

        $choices = array_map(function (Region $region) {
            return $region->getName();
        }, $regions);

        $builder->add('title', TextType::class, [
            'label' => 'Название',
        ])->add('regions', ChoiceType::class, [
            'label' => 'Регионы',
            'required' => true,
            'multiple' => true,
            'choices' => array_flip($choices),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
            'isNew' => false,
            'documentManager' => null,
        ]);
    }
}