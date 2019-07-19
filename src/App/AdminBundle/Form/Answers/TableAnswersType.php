<?php

namespace App\AdminBundle\Form\Answers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class TableAnswersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'required' => true,
            'label' => 'Заголовок таблицы',
            'mapped' => false,
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
            ]),
            'required' => true,
            'mapped' => false,
        ]);

        $formModifier = function (FormInterface $form, $question = null, $answers = null) {
            if ($question) {
                for ($i = 1; $i <= intval($question); $i++) {
                    $form->add('answer_'.$i, TextType::class, [
                        'label' => 'Вариант ответа_'.$i,
                        'mapped' => false,
                        'data' => is_null($answers) ? '' : $answers[$i - 1],
                    ]);

                }
            }
        };

//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                $data = $event->getData();
//
//                $formModifier($event->getForm(), $data->getCountVariants(), $data->getAnswers());
//            }
//        );

        $builder->get('countVariants')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $question = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $question);
            }
        );
    }

}