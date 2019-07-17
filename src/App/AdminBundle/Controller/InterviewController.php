<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Aggregation\InterviewAggregation;
use App\AdminBundle\Document\Interview;
use App\AdminBundle\Filter\InterviewFilter;
use App\AdminBundle\Form\Interview\InterviewFilterType;
use App\AdminBundle\Form\Interview\InterviewType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InterviewController extends BaseController
{
    public function listAction(Request $request)
    {
        $filter = new InterviewFilter();

        $form = $this->createFilterForm(InterviewFilterType::class, $filter);
        $form->handleRequest($request);

        $dm = $this->container->getDocumentManager();
        $qb = $dm->getRepository(Interview::class)->createFilteredQueryBuilder($filter);

        $pagination = $this->paginate($qb);

        return $this->render('@Admin/Interview/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    public function newAction(Request $request)
    {
        $question = new Interview();

        return $this->edit($request, $question);
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->container->getDocumentManager();
        $interview = $dm->getRepository(Interview::class)->find($id);

        if (!$interview) {
            throw new NotFoundHttpException();
        }

        return $this->edit($request, $interview);
    }

    private function edit(Request $request, Interview $interview)
    {

        $isNew = !$interview->getId();

        $questionFormClass = InterviewType::class;

        $form = $this->createForm($questionFormClass, $interview, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);


        if ($this->isValidForm($form)) {
            $dm = $this->container->getDocumentManager();
            $dm->persist($interview);
            $dm->flush();

            if ($isNew) {
                $this->addFlash('success', 'Опрос успешно добавлен');
                $template = 'admin_interview_edit';
            } else {
                $this->addFlash('success', 'Опрос успешно обновлен');
                $template = 'admin_interview_list';
            }

            return $this->redirectToRoute($template, ['id' => $interview->getId()]);

        }

        $template = $isNew ? '@Admin/Interview/new.html.twig' : '@Admin/Interview/edit.html.twig';

        return $this->render($template, [
            'form' => $form->createView(),
            'interview' => $interview,
            'isNew' => $isNew,
        ]);
    }

    public function getInterviewAction($hash)
    {
        $dm = $this->container->getDocumentManager();
        $interview = $dm->getRepository(Interview::class)->findOneBy([
            'hash' => $hash,
        ]);

        $serializer = $this->get('jms_serializer');

        $answers = [];
        foreach ($interview->getQuestions() as $q) {
            foreach ($q->getAnswers() as $k => $v) {
                $answers[] = ['id' => $k, 'value' => $v];
            }
            $q->setAnswers($answers);
            $answers = [];
        }

        $result = $serializer->serialize($interview, 'json');

        $response = new Response($result);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        if (!$interview) {
            $response->setStatusCode(404);
        }

        return $response;
    }

}