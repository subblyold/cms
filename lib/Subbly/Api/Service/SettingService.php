<?php

namespace Subbly\Api\Service;

use Subbly\Model;

class SettingService extends Service
{
    public function init()
    {

    }

    /**
     *
     */
    public function get($key)
    {

    }

    /**
     *
     */
    public function has($key)
    {

    }

    /**
     *
     */
    public function add($key, $value)
    {

    }

    /**
     *
     */
    private function updateCache()
    {
        $key = sprintf('ssc:%s:%s', $scope, $key);
        Cache::put($key, 'value', $minutes);
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.setting';
    }
}
