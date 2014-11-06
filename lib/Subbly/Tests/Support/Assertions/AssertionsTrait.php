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
        $this->assertTrue($this->isDateTimeString($date, $format), sprintf(
            'Failed asserting that %s date is of format "%s".',
            $date,
            $format
        ));
    }
}
