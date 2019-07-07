<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Document\Question;
use App\AdminBundle\Filter\QuestionFilter;
use App\AdminBundle\Form\Question\QuestionFilterType;
use App\AdminBundle\Form\Question\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionController extends BaseController
{
    public function listAction(Request $request)
    {
        $filter = new QuestionFilter();

        $form = $this->createFilterForm(QuestionFilterType::class, $filter);
        $form->handleRequest($request);

        $dm = $this->container->getDocumentManager();
        $qb = $dm->getRepository(Question::class)->createFilteredQueryBuilder($filter);

        $pagination = $this->paginate($qb);

        return $this->render('@Admin/Question/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    public function newAction(Request $request)
    {
        $question = new Question();

        return $this->edit($request, $question);
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->container->getDocumentManager();
        $question = $dm->getRepository(Question::class)->find($id);

        if (!$question) {
            throw new NotFoundHttpException();
        }

        return $this->edit($request, $question);
    }

    private function edit(Request $request, Question $question)
    {

        $isNew = !$question->getId();

        $questionFormClass = QuestionType::class;


        $form = $this->createForm($questionFormClass, $question, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);


        if ($this->isValidForm($form)) {

            $dm = $this->container->getDocumentManager();
            $dm->persist($question);
            $dm->flush();

            $this->addFlash('success', 'Вопрос успешно добавлен');

            return $this->redirectToRoute('admin_question_edit', ['id' => $question->getId()]);

        }


        return $this->render('@Admin/Question/edit.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'isNew' => $isNew,
        ]);
    }
}