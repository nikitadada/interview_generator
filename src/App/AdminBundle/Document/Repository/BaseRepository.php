<?php

namespace App\AdminBundle\Document\Repository;

use Doctrine\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\DocumentRepository;

class BaseRepository extends DocumentRepository
{
    protected function textSearch(Builder $builder, $text)
    {
        $builder->equals(new \MongoRegex('/' . preg_quote($text) . '/i'));
    }

    public function getCollection()
    {
        return $this->getDocumentManager()->getDocumentCollection($this->getDocumentName());
    }
}
