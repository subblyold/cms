<?php

namespace Subbly\Api\Service;

use Illuminate\Support\Facades\Event;

use Subbly\Api\Api;
use Subbly\Model\ModelInterface;

abstract class Service
{
    /** @var Subbly\Api\Api $api **/
    private $api;

    /**
     * The constructor.
     *
     * @param Subbly\Api\Api  $api The Api class
     *
     * @throws Subbly\Api\Service\Exception Throw an exception if name() method does not return a string
     */
    final public function __construct(Api $api)
    {
        if (!is_string($this->name())) {
            throw new Exception(sprintf('"%s"::name() method must return a string'),
                __CLASS__
            );
        }

        $this->api = $api;

        $this->init();
    }

    /**
     * Name of the service
     * Must be unique
     *
     * Example of value: 'subbly.user'
     *
     * @return string
     */
    abstract public function name();

    /**
     * Service initialization
     *
     * @api
     */
    protected function init() {}

    /**
     * Fire an event
     *
     * @param string  $eventName Name of the event
     * @param array   $vars      Event vars
     *
     * @return Event
     *
     * @api
     */
    protected function fireEvent($eventName, array $vars = array())
    {
        return Event::fire(
            sprintf('%s:%s', $this->name(), $eventName),
            $vars
        );
    }

    /**
     * Get the Api class
     *
     * @return Subbly\Api\Api
     */
    final protected function api()
    {
        return $this->api;
    }
}
