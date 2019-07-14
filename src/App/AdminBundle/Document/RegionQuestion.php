<?php

namespace App\AdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\EmbeddedDocument
 */
class RegionQuestion
{
    /**
     * @Mongo\EmbedOne(targetDocument="Region")
     */
    private $region = 2;

    /**
     * @Mongo\EmbedMany(targetDocument="Question")
     */
    private $questions = [];


    public function getRegion()
    {
        return $this->region;
    }

    public function setRegion($region)
    {
        $this->region = $region;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

}