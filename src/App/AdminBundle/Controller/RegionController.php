<?php

namespace App\AdminBundle\Controller;


use App\AdminBundle\Document\Region;
use App\AdminBundle\Form\RegionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegionController extends BaseController
{
    public function listAction()
    {
        $dm = $this->container->getDocumentManager();
        $tags = $qb = $dm->getRepository(Region::class)->findAll();

        return $this->render('@Admin/Region/list.html.twig', [
            'items' => $tags,
        ]);
    }

    public function newAction(Request $request)
    {
        $region = new Region();

        return $this->edit($request, $region);
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->container->getDocumentManager();
        $region = $dm->getRepository(Region::class)->find($id);

        if (!$region) {
            throw new NotFoundHttpException();
        }

        return $this->edit($request, $region);
    }

    private function edit(Request $request, Region $region)
    {
        $isNew = !$region->getId();

        $regionFormClass = RegionType::class;

        $form = $this->createForm($regionFormClass, $region, [
            'isNew' => $isNew,
        ]);

        $form->handleRequest($request);

        if ($this->isValidForm($form)) {
            $dm = $this->container->getDocumentManager();
            $dm->persist($region);
            $dm->flush();

            $this->addFlash('success', 'Регион успешно добавлен');

            return $this->redirectToRoute('admin_region_edit', ['id' => $region->getId()]);
        }

        return $this->render('@Admin/Region/edit.html.twig', [
            'form' => $form->createView(),
            'questionTag' => $region,
            'isNew' => $isNew,
        ]);
    }
}