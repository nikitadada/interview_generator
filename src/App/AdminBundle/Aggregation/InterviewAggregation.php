<?php

namespace App\AdminBundle\Aggregation;

use App\AdminBundle\Document\Interview;
use App\AdminBundle\Document\Region;
use App\AdminBundle\Filter\InterviewFilter;
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

    private $pipeline;
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

        $match = [];
        if ($filter->getTitle()) {
            $match['title'] = $filter->getTitle()->getTitle();
        }

        if ($filter->getRegions()) {
            $ids = array_map(function ($r) {
                return $r->getId();
            }, $filter->getRegions());
            $match['regions'] = ['$in' => $ids];
        }

        if ($filter->getDateRange()) {
            $match['createdAt'] = [
                '$gte' => new \MongoDate($filter->getDateRange()->getFrom()->setTime(0, 0, 0)->getTimestamp()),
                '$lte' => new \MongoDate($filter->getDateRange()->getTo()->setTime(23, 59, 59)->getTimestamp()),
            ];
        }

        $match = (object)$match;

        $this->pipeline = [
            ['$match' => $match],
        ];

        $this->pipeline[] = ['$sort' => [$this->sortField => $this->sortDirection]];

        if ($this->offset) {
            $this->pipeline[] = ['$skip' => $this->offset];
        }
        if ($this->limit) {
            $this->pipeline[] = ['$limit' => $this->limit];
        }

        $this->items = $this
            ->collection
            ->aggregate([$this->pipeline], ['cursor' => array('batchSize' => 0)])->toArray();


        $regionNames = $this
            ->dm
            ->getDocumentCollection(Region::class)
            ->find()->toArray();

        foreach ($this->items as &$item) {
            $item['createdAt'] = $item['createdAt']->toDatetime();
            foreach ($item['regions'] as $key => $value) {
                $item['regions'][$key] = $regionNames[$value]['name'];
            }
        }
    }
}
