<?php

namespace App\AdminBundle\Filter;

class QuestionFilter
{
    /**
     * @var DateRange
     */
    protected $dateRange;

    private $title;

    public function __construct()
    {
        $this->dateRange = new DateRange();
    }

    public function getDateRange()
    {
        return $this->dateRange;
    }

    public function setDateRange($dateRange)
    {
        $this->dateRange = $dateRange;

        return $this;
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


}
