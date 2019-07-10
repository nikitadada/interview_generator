<?php

namespace App\AdminBundle\Aggregation;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Document\Region;
use App\AdminBundle\Filter\InterviewFilter;
use Doctrine\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\DocumentManager;

class InterviewAggregation
{
    /**
     * @var InterviewFilter
     */
    private $filter;

    private $sortField;
    private $sortDirection;
    private $limit;
    private $offset;

    /**
     * @var DocumentManager
     */
    private $dm;

    private $initialized = false;
    private $items = [];
    private $totalCount;

    /**
     * @var \Doctrine\MongoDB\Collection
     */
    private $collection;

    public function __construct(
        DocumentManager $dm,
        InterviewFilter $filter,
        $sortField = '_id',
        $sortDirection = null,
        $limit = null,
        $offset = null
    ) {
        $this->dm = $dm;

        $this->filter = $filter;
        $this->limit = +$limit;
        $this->offset = +$offset;
        $this->sortField = $sortField;
        $this->sortDirection = strtoupper($sortDirection) == 'ASC' ? 1 : -1;

        $this->collection = $dm->getDocumentCollection(Interview::class);
    }

    public function getItems()
    {
        $this->init();

        return $this->items;
    }

    public function getTotalCount()
    {
        if (null === $this->totalCount) {
            $this->init();
        }

        return $this->totalCount;
    }

    public function init()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        $filter = $this->filter;

        $qb = $this->dm->getRepository(Interview::class)->createQueryBuilder();
        $qb->hydrate(false);

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
            $qb->field('regions')->in($ids);
        }

        if ($filter->getTitle()) {
            $this->textSearch($qb->field('title'), $filter->getTitle()->getTitle());
        }

        if ($this->offset) {
            $qb->skip($this->offset);
        }
        if ($this->limit) {
            $qb->limit($this->limit);
        }

        $this->items = $qb->getQuery()->execute()->toArray();

        $regionNames = $this
            ->dm
            ->getDocumentCollection(Region::class)
            ->find()->toArray();

        foreach ($this->items as &$item) {
            $item['createdAt'] = $item['createdAt']->toDatetime();
            if (array_key_exists('regions', $item)) {
                foreach ($item['regions'] as $key => $value) {
                    $item['regions'][$key] = $regionNames[$value]['name'];
                }
            }
        }

    }

    protected function textSearch(Builder $builder, $text)
    {
        $builder->equals(new \MongoRegex('/'.preg_quote($text).'/i'));
    }
}
