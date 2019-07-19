<?php

namespace App\AdminBundle\Form\Transformer;

use App\AdminBundle\Filter\DateRange;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateRangeTransformer implements DataTransformerInterface
{
    protected $format;

    public function __construct($format = 'd.m.Y')
    {
        $this->format = $format;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof DateRange) {
            throw new TransformationFailedException('Expected DateRange object');
        }

        $from = $value->getFrom() ? $value->getFrom()->format($this->format) : '';
        $to = $value->getTo() ? $value->getTo()->format($this->format) : '';

        return $from . ' - ' . $to;
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $parts = preg_split('/\s+-\s+/', $value, 2, PREG_SPLIT_NO_EMPTY);

        if (1 === count($parts)) {
            $parts[1] = $parts[0];
        }

        list($from, $to) = $parts;

        $range = new DateRange();
        $range
            ->setFrom(\DateTime::createFromFormat($this->format, $from))
            ->setTo(\DateTime::createFromFormat($this->format, $to))
        ;

        return $range;
    }
}
