<?php

namespace App\AdminBundle\Filter;

class DateRange
{
    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $to;

    public function __construct()
    {
        $this->from = new \DateTime('2019-01-01');
        $this->to = new \DateTime();
    }

    public static function createFullPeriod()
    {
        $dateRange = new DateRange();
        $dateRange->setFrom(new \DateTime('2018-12-01'));
        return $dateRange;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($dateFrom)
    {
        $this->from = $dateFrom;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($dateTo)
    {
        $this->to = $dateTo;
        return $this;
    }
}
