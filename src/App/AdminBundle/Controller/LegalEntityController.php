<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Document\LegalEntity;
use App\AdminBundle\Form\LegalEntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LegalEntityController extends BaseController
{
    public function listAction()
    {
        $dm = $this->container->getDocumentManager();
        $tags = $qb = $dm->getRepository(LegalEntity::class)->findAll();

        return $this->render('@Admin/LegalEntity/list.html.twig', [
            'items' => $tags,
        ]);
    }

    public function newAction(Request $request)
    {
        $legalEntity = new LegalEntity();

        return $this->edit($request, $legalEntity);
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->container->getDocumentManager();
        $legalEntity = $dm->getRepository(LegalEntity::class)->find($id);

        if (!$legalEntity) {
            throw new NotFoundHttpException();
        }

        return $this->edit($request, $legalEntity);
    }

    private function edit(Request $request, LegalEntity $legalEntity)
    {
        $isNew = !$legalEntity->getId();

        $legalEntityFormClass = LegalEntityType::class;

        $form = $this->createForm($legalEntityFormClass, $legalEntity, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);

        if ($this->isValidForm($form)) {
            $dm = $this->container->getDocumentManager();
            $dm->persist($legalEntity);
            $dm->flush();

            $this->addFlash('success', 'Юр. лицо успешно добавлено');

            return $this->redirectToRoute('admin_legal_entity_edit', ['id' => $legalEntity->getId()]);
        }

        return $this->render('@Admin/LegalEntity/edit.html.twig', [
            'form' => $form->createView(),
            'legalEntity' => $legalEntity,
            'isNew' => $isNew,
        ]);
    }
}