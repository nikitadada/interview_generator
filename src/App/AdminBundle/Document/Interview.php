<?php

namespace App\AdminBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document(collection="interviews", repositoryClass="App\AdminBundle\Document\Repository\InterviewRepository")
 */
class Interview
{
    /**
     * @Mongo\Id(strategy="INCREMENT")
     */
    private $id;

    /**
     * @Mongo\Field(type="string")
     */
    private $title;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $createdAt;

    /**
     * @Mongo\ReferenceMany(targetDocument="Region", storeAs="id")
     */
    private $regions = [];

    /**
     * @Mongo\ReferenceMany(targetDocument="Question", storeAs="id")
     */
    private $questions = [];


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->regions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getRegions()
    {
        return $this->regions;
    }

    public function setRegions($regions)
    {
        $this->regions = $regions;
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