<?php

namespace App\AdminBundle\Document;

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


    public function __construct()
    {
        $this->createdAt = new \DateTime();
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


}