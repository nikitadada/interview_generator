<?php

namespace App\AdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document(collection="questions", repositoryClass="App\AdminBundle\Document\Repository\QuestionRepository")
 */
class Question
{
    const QUESTION_TYPE_SINGLE = 'single';
    const QUESTION_TYPE_SINGLE_COMMENT = 'single_comment';
    const QUESTION_TYPE_DETAILED = 'detailed';
    const QUESTION_TYPE_MULTIPLE = 'multiple';
    const QUESTION_TYPE_MULTIPLE_COMMENT = 'multiple_comment';
    const QUESTION_TYPE_TABLE = 'table';

    const TYPES = [
        self::QUESTION_TYPE_SINGLE => 'Одиночный выбор',
        self::QUESTION_TYPE_SINGLE_COMMENT => 'Одиночный выбор с комментарием',
        self::QUESTION_TYPE_DETAILED => 'Развернутый ответ',
        self::QUESTION_TYPE_MULTIPLE => 'Множественный выбор',
        self::QUESTION_TYPE_MULTIPLE_COMMENT => 'Множественный выбор с комментарием',
        self::QUESTION_TYPE_TABLE => 'Табличный со шкалой оценки',
    ];

    /**
     * @Mongo\Id(strategy="INCREMENT")
     */
    private $id;

    /**
     * @Mongo\Field(type="string")
     */
    private $title;

    /**
     * @Mongo\Field(type="string")
     */
    private $type;

    /**
     * @Mongo\Field(type="string")
     */
    private $countVariants;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    private $createdAt;

    /**
     * @Mongo\ReferenceOne(targetDocument="QuestionTag", inversedBy="questions", storeAs="id")
     * @var QuestionTag
     */
    private $questionTag;


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
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCountVariants()
    {
        return $this->countVariants;
    }

    public function setCountVariants($countVariants)
    {
        $this->countVariants = $countVariants;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getQuestionTag()
    {
        return $this->questionTag;
    }

    public function setQuestionTag($questionTag)
    {
        $this->questionTag = $questionTag;
    }

}