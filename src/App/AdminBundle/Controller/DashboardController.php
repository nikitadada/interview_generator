<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Filter\InterviewFilter;
use App\AdminBundle\Form\Interview\InterviewFilterType;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends BaseController
{
    public function indexAction(Request $request)
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
}
