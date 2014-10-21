<?php

namespace Subbly\Api\Service;

class Exception extends \Exception
{
    const CANT_CREATE_MODEL = 'Can\'t create the "%s" model in Service named "%s". Format invalid.';
    const CANT_UPDATE_MODEL = 'Can\'t update the "%s" model in Service named "%s". Format invalid.';

    /** SettingService */
    const SETTING_KEY_NOT_EXISTS = 'Setting key named "%s" does not exists';
}
