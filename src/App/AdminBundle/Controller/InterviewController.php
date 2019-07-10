<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Filter\InterviewFilter;
use App\AdminBundle\Form\Interview\InterviewFilterType;
use App\AdminBundle\Form\Interview\InterviewType;
use Symfony\Component\HttpFoundation\Request;
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
            'documentManager' => $this->container->getDocumentManager(),
        ]);

        $form->handleRequest($request);


        if ($this->isValidForm($form)) {
            $dm = $this->container->getDocumentManager();
            $dm->persist($interview);
            $dm->flush();

            $this->addFlash('success', 'Опрос успешно добавлен');

            return $this->redirectToRoute('admin_interview_list', ['id' => $interview->getId()]);

        }


        return $this->render('@Admin/Interview/edit.html.twig', [
            'form' => $form->createView(),
            'question' => $interview,
            'isNew' => $isNew,
        ]);
    }

}