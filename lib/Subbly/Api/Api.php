<?php

namespace Subbly\Api;

use Subbly\Api\Service\Service;
use Subbly\Core\Container;

class Api
{
    /** @var Subbly\Core\Container $container */
    private $container;

    /** @var ArrayObject $services */
    private $services;

    /**
     * The constructor.
     *
     * @param Container  $container          The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->services  = new \ArrayObject();
    }

    /**
     * Get a service.
     *
     * @param string  $name The name of the service
     *
     * @return \Subbly\Api\Service\Service
     *
     * @throws \Subbly\Api\Exception Throw an exception if the service name does not exists
     *
     * @api
     */
    public function service($name)
    {
        if (!$this->services->offsetExists($name)) {
            throw new Exception(sprintf(Exception::SERVICE_NOT_EXISTS, $name));
        }

        return $this->services->offsetGet($name);
    }

    /**
     * Process to the services registration.
     *
     * @param array  $servicesToRegister The list of the services must be registred
     */
    public function registerServices(array $servicesToRegister)
    {
        foreach ($servicesToRegister as $className) {
            $this->registerService($className);
        }
    }

    /**
     * Process the registration of one service.
     *
     * @param string  $className The class name of the service
     *
     * @throws \Subbly\Api\Exception Throw an exception if class name does not exists
     * @throws \Subbly\Api\Exception Throw an exception if the class does not implement \Subbly\Api\Service\Service
     * @throws \Subbly\Api\Exception Throw an exception if service name is already register
     */
    public function registerService($className)
    {
        if (! class_exists($className)) {
            throw new Exception(sprintf(Exception::SERVICE_CLASS_NOT_EXISTS, $className));
        }

        $service = new $className($this);

        if (!$service instanceof Service) {
            throw new Exception(sprintf(Exception::NOT_A_SERVICE, $className));
        }
        if ($this->services->offsetExists($service->name())) {
            throw new Exception(sprintf(Exception::SERVICE_ALREADY_EXISTS, $service->name()));
        }

        $this->services->offsetSet($service->name(), $service);
    }
}
