<?php

namespace App\AdminBundle\Form\Question;

use App\AdminBundle\Document\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Заголовок',
        ])->add('countVariants', ChoiceType::class, [
            'label' => 'Количество вариантов ответа',
            'choices' => array_flip([
                0 => '',
                1 => 'Один',
                2 => 'Два',
                3 => 'Три',
                4 => 'Четыре',
                5 => 'Пять',
                6 => 'Шесть',
                7 => 'Семь',
                8 => 'Восемь',
                9 => 'Девять',
                10 => 'Десять',
            ]),
            'required' => true,
        ])->add('type', ChoiceType::class, [
            'label' => 'Тип вопроса',
            'choices' => array_flip(Question::TYPES),
//            'mapped' => false,
            'disabled' => true,
        ]);


        $formModifier = function (FormInterface $form, $question = null) {
            if ($question) {
                $form->add('type', ChoiceType::class, [
                    'label' => 'Тип вопроса',
                    'choices' => array_flip(Question::TYPES),
//                    'mapped' => false,
                ]);
            }

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getCountVariants());
            }
        );

        $builder->get('countVariants')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $question = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $question);
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'isNew' => false,
        ]);
    }
}