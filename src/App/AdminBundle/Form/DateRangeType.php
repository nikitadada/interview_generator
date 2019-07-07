<?php

namespace App\AdminBundle\Form;

use App\AdminBundle\Form\Transformer\DateRangeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new DateRangeTransformer();

        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Период',
            'by_reference' => false,
            'empty_data' => function (FormInterface $form) {
                return $form->getNormData();
            }
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
