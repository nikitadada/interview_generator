<?php

namespace App\AdminBundle\Document\Repository;

use App\AdminBundle\Filter\QuestionFilter;

class QuestionRepository extends BaseRepository
{
    public function createFilteredQueryBuilder(QuestionFilter $filter)
    {
        $qb = $this->createQueryBuilder();

        if ($filter->getDateRange()) {
            $qb
                ->field('createdAt')
                ->gte($filter->getDateRange()->getFrom()->setTime(0, 0, 0))
                ->lte($filter->getDateRange()->getTo()->setTime(23, 59, 59));
        }

        if ($filter->getTitle()) {
            $this->textSearch($qb->field('title'), $filter->getTitle()->getTitle());
        }

        if ($filter->getQuestionTag()) {
            $qb->field('questionTag')->references($filter->getQuestionTag());
        }

        return $qb;
    }
}