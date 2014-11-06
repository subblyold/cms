<?php

namespace Subbly\Tests\Support\Assertions;

trait AssertionsTrait
{
    /**
     * Assert a date format from a string date
     *
     * @param string  $date   The date into a string format
     * @param string  $format The date format to check
     */
    public function assertDateTimeString($date, $format)
    {
        $d = \DateTime::createFromFormat($format, $date);
        $this->assertEquals($d->format($format), $date);
    }
}
