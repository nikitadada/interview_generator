<?php

namespace App\AdminBundle\Document\Repository;

use App\AdminBundle\Filter\InterviewFilter;

class InterviewRepository extends BaseRepository
{
    public function createFilteredQueryBuilder(InterviewFilter $filter)
    {
        $qb = $this->createQueryBuilder();

        if ($filter->getDateRange()) {
            $qb
                ->field('createdAt')
                ->gte($filter->getDateRange()->getFrom()->setTime(0, 0, 0))
                ->lte($filter->getDateRange()->getTo()->setTime(23, 59, 59));
        }

        if ($filter->getRegions()) {
            $ids = array_map(function ($r) {
                return $r->getId();
            }, $filter->getRegions());

            $qb->field('regions.$id')->in($ids);
        }

        if ($filter->getLegalEntities()) {
            $ids = array_map(function ($r) {
                return $r->getId();
            }, $filter->getLegalEntities());

            $qb->field('legalEntities.$id')->in($ids);
        }

        if ($filter->getTitle()) {
            $this->textSearch($qb->field('title'), $filter->getTitle()->getTitle());
        }

        return $qb;
    }
}