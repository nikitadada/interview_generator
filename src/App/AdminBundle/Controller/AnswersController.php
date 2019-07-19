<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Document\Question;
use App\AdminBundle\Form\Answers\AnswersType;
use App\AdminBundle\Form\Answers\TableAnswersType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AnswersController extends BaseController
{
    public function formAction()
    {
        $questionFormClass = AnswersType::class;


        $form = $this->createForm($questionFormClass);

        return $this->render('@Admin/Answers/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function tableFormAction()
    {
        $questionFormClass = TableAnswersType::class;
        $form = $this->createForm($questionFormClass);

        return $this->render('@Admin/Answers/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function addAction(Request $request)
    {
        $id = $request->get('id');

        $dm = $this->container->getDocumentManager();
        $question = $dm->getRepository(Question::class)->find($id);

        $answer = $request->get('answers')['answers'];

        $oldAnswers = $question->getAnswers();

        if (is_null($oldAnswers)) {
            $answers = [$answer];
        } else {
            $answers = array_merge($oldAnswers, [$answer]);
        }

        $question->setAnswers($answers);

        $dm->persist($question);
        $dm->flush();

        $this->addFlash('success', 'Вариант ответа добавлен к вопросу');

        return $this->redirectToRoute('admin_question_edit', ['id' => $question->getId()]);

    }


    public function addTableAction(Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $answers = $request->get('answers');

        $values = [];
        foreach ($answers as $k => $v) {
            if ($v != '') {
                $values[] = ['id' => $k, 'value' => $v];
            }
        }

        $table[0] = [
            'id' => 0,
            'name' => $title,
            'values' => $values,
        ];

        $dm = $this->container->getDocumentManager();
        $question = $dm->getRepository(Question::class)->find($id);

        $oldAnswers = $question->getAnswers();

        if (!is_null($oldAnswers)) {
            $answers = array_merge($oldAnswers, $table);
        } else {
            $answers = $table;
        }

        $question->setAnswers($answers);

        $dm->persist($question);
        $dm->flush();

        $this->addFlash('success', 'Вариант ответа добавлен к вопросу');

        return new JsonResponse('ok');

    }
}