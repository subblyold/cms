<?php

namespace Subbly\Api;

class Exception extends \Exception
{
    const SERVICE_NOT_EXISTS       = 'Service named "%s" does not exists';
    const SERVICE_CLASS_NOT_EXISTS = 'Service with class "%s" does not exists';
    const NOT_A_SERVICE            = 'Class "%s" is not a Subbly\Api\Service\Service';
    const SERVICE_ALREADY_EXISTS   = 'Service named "%s" with class "%s" already exists';
}
