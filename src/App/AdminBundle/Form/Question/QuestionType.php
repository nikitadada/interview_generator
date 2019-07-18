<?php

namespace App\AdminBundle\Form\Question;

use App\AdminBundle\Document\Question;
use Sirian\SuggestBundle\Form\Type\SuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        ])->add('required', CheckboxType::class, [
            'label' => 'Обязательный',
            'required' => false,
            'data' => true,

        ])->add('questionTag', SuggestType::class, [
            'suggester' => 'question_tag',
            'required' => false,
            'label' => 'Тема вопроса',
        ])->add('type', ChoiceType::class, [
            'label' => 'Тип вопроса',
            'choices' => array_flip(Question::TYPES),
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'isNew' => false,
        ]);
    }
}