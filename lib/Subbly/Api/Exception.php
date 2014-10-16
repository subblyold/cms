<?php

namespace Subbly\Api;

class Exception extends \Exception
{
    const SERVICE_NOT_EXISTS       = 'Service named "%" does not exists';
    const SERVICE_CLASS_NOT_EXISTS = 'Service with class "%" does not exists';
    const NOT_A_SERVICE            = 'Class "%" is not a Subbly\Api\Service';
    const SERVICE_ALREADY_EXISTS   = 'Service named "%s" with class "%" already exists';
}
