<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Aggregation\InterviewAggregation;
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

        $limit = 50;
        $page = +$request->query->get('page', 1);

        $aggregation = new InterviewAggregation(
            $this->container->getDocumentManager(),
            $filter,
            $request->query->get('sort', '_id'),
            $request->query->get('direction', 'DESC'),
            $limit,
            ($page - 1) * $limit
        );

        $pagination = $this->paginate([], $limit);
        $pagination->setItems($aggregation->getItems());

        return $this->render('@Admin/Interview/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
