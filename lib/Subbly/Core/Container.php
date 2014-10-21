<?php

namespace Subbly\Core;

use Pimple;

use Subbly\Api\Api;

class Container extends Pimple\Container
{
    /**
     * Load services
     */
    public function load()
    {
        $c = $this;

        $this['api'] = function () use ($c) {
            $api = new Api($c, array(
                'Subbly\\Api\\Service\\OrderService',
                'Subbly\\Api\\Service\\ProductService',
                'Subbly\\Api\\Service\\SettingService',
                'Subbly\\Api\\Service\\UserService',
            ));

            $api->service('subbly.setting')->registerDefaultSettings(
                __DIR__ . '/../Resources/configs/default_settings.yml'
            );

            return $api;
        };
    }

    /**
     * Ask if named service exists
     *
     * @param string  $name  Name of the service to check
     *
     * @return boolean
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a service from is name
     *
     * @param string  $name  Name of the service to called
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->offsetGet($name);
    }
}
