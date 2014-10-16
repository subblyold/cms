<?php

namespace Subbly\Api;

use Subbly\Container;

class Api
{
    private $container;

    private $services;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->services  = new \ArrayObject();

        $this->registerServices();
    }

    public function get($name)
    {
        if (!$this->services->offsetExists($name)) {
            throw new Exception(sprintf(Exception::SERVICE_NOT_EXISTS, $name));
        }
    }

    private function registerServices()
    {
        $servicesDefinition = array(
            'subbly.user' => 'Subbly\\Api\\Service\\User',
        );
    }

    private function registerService($name, $className)
    {
        if (! class_exists($className)) {
            throw new Exception(sprintf(Exception::SERVICE_CLASS_NOT_EXISTS,
                $name,
                $className
            ));
        }
        if ($this->services->offsetExists($name)) {
            throw new Exception(sprintf(Exception::SERVICE_ALREADY_EXISTS, $name));
        }

        $service = new $className();

        if (!$service instanceof Service) {
            throw new Exception(sprintf(Exception::NOT_A_SERVICE, $className));
        }

        $this->services->offsetSet($name, $service);
    }
}
