<?php

namespace App\AdminBundle\Filter;

class InterviewFilter
{
    /**
     * @var DateRange
     */
    private $dateRange;

    private $title;

    private $regions;


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

    public function getRegions()
    {
        return $this->regions;
    }

    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

}
