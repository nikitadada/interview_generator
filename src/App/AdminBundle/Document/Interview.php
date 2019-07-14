<?php

namespace App\AdminBundle\Document;

use App\AdminBundle\Filter\DateRange;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @Mongo\Document(collection="interviews", repositoryClass="App\AdminBundle\Document\Repository\InterviewRepository")
 * @ExclusionPolicy("all")
 */
class Interview
{
    /**
     * @Mongo\Id(strategy="INCREMENT")
     * @Expose
     */
    private $id;

    /**
     * @Mongo\Field(type="string")
     * @Expose
     */
    private $title;

    /**
     * @var DateRange
     */
    private $dateRange;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $from;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $to;

    /**
     * @Mongo\ReferenceMany(targetDocument="Region", storeAs="id")
     * @Expose
     */
    private $regions = [];

    /**
     * @Mongo\EmbedMany(targetDocument="Question")
     * @Expose
     */
    private $questions = [];

    /**
     * @Mongo\EmbedMany(targetDocument="RegionQuestion")
     * @Expose
     */
    private $regionQuestions = [];

    /**
     * @Mongo\Field(type="string")
     */
    private $hash;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->regions = new ArrayCollection();
        $this->hash = substr(md5($this->getCreatedAt()->format('Y-m-d H:i:s')), 0, 8);
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

    public function getRegionQuestions()
    {
        return $this->regionQuestions;
    }

    public function setRegionQuestions($regionQuestions)
    {
        $this->regionQuestions = $regionQuestions;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getDateRange()
    {
        return $this->dateRange;
    }

    public function setDateRange($dateRange)
    {
        $this->dateRange = $dateRange;
    }


}