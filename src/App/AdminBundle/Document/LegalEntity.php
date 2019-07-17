<?php

namespace App\AdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @Mongo\Document(collection="legal_entities")
 * @ExclusionPolicy("all")
 */
class LegalEntity
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
    private $name;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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