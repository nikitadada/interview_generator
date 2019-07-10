<?php

namespace App\AdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document(collection="regions")
 */
class Region
{
    /**
     * @Mongo\Id(strategy="INCREMENT")
     */
    private $id;

    /**
     * @Mongo\Field(type="string")
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