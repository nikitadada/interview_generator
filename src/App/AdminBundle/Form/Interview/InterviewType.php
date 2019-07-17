<?php

namespace App\AdminBundle\Form\Interview;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Document\Region;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Название',
        ])->add('hash', TextType::class, [
            'label' => 'Токен (отображается в ссылке)',
        ])->add('regions', DocumentType::class, [
            'class' => Region::class,
            'label' => 'Регионы',
            'required' => true,
            'multiple' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
            'isNew' => false,
        ]);
    }
}