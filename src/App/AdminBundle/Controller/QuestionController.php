<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Document\Question;
use App\AdminBundle\Filter\QuestionFilter;
use App\AdminBundle\Form\Interview\SuggestQuestionsType;
use App\AdminBundle\Form\Question\QuestionFilterType;
use App\AdminBundle\Form\Question\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionController extends BaseController
{
    public function formAction()
    {
        $questionFormClass = QuestionType::class;

        $question = new Question();

        $form = $this->createForm($questionFormClass, $question);

        return $this->render('@Admin/Question/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function suggestFormAction()
    {
        $questionFormClass = SuggestQuestionsType::class;

        $interview = new Interview();

        $form = $this->createForm($questionFormClass, $interview);

        return $this->render('@Admin/Question/suggest_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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

    public function addToInterviewAction(Request $request)
    {
        $question = new Question();

        $id = $request->get('id');

        return $this->edit($request, $question, $id);
    }

    public function addToInterviewFromBankAction(Request $request)
    {
        $id = $request->get('id');

        $dm = $this->container->getDocumentManager();
        $interview = $dm->getRepository(Interview::class)->find($id);

        $questionFormClass = SuggestQuestionsType::class;

        $form = $this->createForm($questionFormClass, $interview);

        $oldQuestions = $interview->getQuestions();

        $form->handleRequest($request);

        if ($this->isValidForm($form)) {
            $questions = array_merge($oldQuestions->toArray(), $interview->getQuestions());
            $interview->setQuestions(array_unique($questions));

            $dm->persist($interview);
            $dm->flush();

            $this->addFlash('success', 'Вопросы добавлены к опросу');

            return $this->redirectToRoute('admin_interview_edit', ['id' => $interview->getId()]);
        }

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

    private function edit(Request $request, Question $question, $interviewId = null)
    {

        $isNew = !$question->getId();

        $questionFormClass = QuestionType::class;

        $form = $this->createForm($questionFormClass, $question, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);

        if ($this->isValidForm($form)) {
            $countVariants = $form->get('countVariants')->getData();

            if ($countVariants > 0) {
                $answers = [];

                for ($i = 1; $i <= $countVariants; $i++) {
                    $answers[] = $form->get('answer_'.$i)->getData();
                }

                $question->setAnswers($answers);
            }

            $dm = $this->container->getDocumentManager();
            $dm->persist($question);
            $dm->flush();

            if (!is_null($interviewId)) {
                $this->addFlash('success', 'Вопрос добавлен к опросу');

                $dm = $this->container->getDocumentManager();
                $interview = $dm->getRepository(Interview::class)->find($interviewId);

                $interview->getQuestions()->add($question);

                $dm->persist($interview);
                $dm->flush();

                return $this->redirectToRoute('admin_interview_edit', ['id' => $interview->getId()]);
            }

            $this->addFlash('success', 'Вопрос успешно добавлен');

            return $this->redirectToRoute('admin_question_list', ['id' => $question->getId()]);

        }

        return $this->render('@Admin/Question/edit.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'isNew' => $isNew,
        ]);
    }
}