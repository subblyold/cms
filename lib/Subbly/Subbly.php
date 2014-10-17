<?php

namespace Subbly;

use Subbly\Core\Container;

class Subbly
{
    const VERSION = '0.1.0-pre';

    static private $container;

    private function __construct() {}
    private function __clone () {}

    /**
     * Access to the Subbly Api
     *
     * @param null|string  $serviceName The name of the service (optional)
     *
     * @return Subbly\Api\Api|Subbly\Api\Service
     *
     * @api
     */
    static public function api($serviceName = null)
    {
        $api = self::getContainer()->get('api');

        if ($serviceName !== null) {
            return $api->service($serviceName);
        }

        return $api;
    }

    static private function getContainer()
    {
        if (!(self::$container instanceof Container))
        {
            self::$container = new Container();
            self::$container->load();
        }

        return self::$container;
    }
}
