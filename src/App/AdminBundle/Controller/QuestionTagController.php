<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Document\QuestionTag;
use App\AdminBundle\Form\QuestionTagType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionTagController extends BaseController
{
    public function listAction()
    {
        $dm = $this->container->getDocumentManager();
        $tags = $qb = $dm->getRepository(QuestionTag::class)->findAll();

        return $this->render('@Admin/QuestionTag/list.html.twig', [
            'items' => $tags,
        ]);
    }

    public function newAction(Request $request)
    {
        $questionTag = new QuestionTag();

        return $this->edit($request, $questionTag);
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->container->getDocumentManager();
        $questionTag = $dm->getRepository(QuestionTag::class)->find($id);

        if (!$questionTag) {
            throw new NotFoundHttpException();
        }

        return $this->edit($request, $questionTag);
    }

    private function edit(Request $request, QuestionTag $questionTag)
    {
        $isNew = !$questionTag->getId();

        $questionTagFormClass = QuestionTagType::class;

        $form = $this->createForm($questionTagFormClass, $questionTag, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);

        if ($this->isValidForm($form)) {
            $dm = $this->container->getDocumentManager();
            $dm->persist($questionTag);
            $dm->flush();

            $this->addFlash('success', 'Тема вопроса успешно добавлена');

            return $this->redirectToRoute('admin_question_tag_edit', ['id' => $questionTag->getId()]);
        }

        return $this->render('@Admin/QuestionTag/edit.html.twig', [
            'form' => $form->createView(),
            'questionTag' => $questionTag,
            'isNew' => $isNew,
        ]);
    }
}