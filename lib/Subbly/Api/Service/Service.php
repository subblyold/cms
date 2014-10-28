<?php

namespace Subbly\Api\Service;

use Illuminate\Database\Eloquent\Builder;

use Subbly\Api\Api;
use Subbly\Model\ModelInterface;
use Subbly\Subbly;

abstract class Service
{
    const LIMIT_DEFAULT = 50;
    const LIMIT_MIN     = 5;
    const LIMIT_MAX     = 100;

    /** @var Subbly\Api\Api $api **/
    private $api;

    /** @var */
    protected $modelClass;

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

        /**
         * Initialization
         */
        if ($this->fireEvent('initializing', array($this)) === false) return false;

        $this->init();

        $this->fireEvent('initialized', array($this));
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
     * Get new query instance
     *
     * @param array  $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newQuery(array $options = array())
    {
        $options = array_replace(array(
            'offset' => 0,
            'limit'  => self::LIMIT_DEFAULT,
        ), $options);

        $query = call_user_func($this->modelClass . '::query');

        if (is_integer($options['offset'])) {
            $query->offset((int) $options['offset']);
        }
        if (is_integer($options['limit']))
        {
            if ($options['limit'] < self::LIMIT_MIN) {
                $options['limit'] = self::LIMIT_MIN;
            }
            else if ($options['limit'] > self::LIMIT_MAX) {
                $options['limit'] = self::LIMIT_MAX;
            }

            $query->limit((int) $options['limit']);
        }

        return $query;
    }

    /**
     * Fire an event
     *
     * @param string   $eventName Name of the event
     * @param array    $vars      Event vars
     * @param boolean  $halt
     *
     * @return array|null
     *
     * @api
     */
    protected function fireEvent($eventName, array $vars = array(), $halt = true)
    {
        $method    = $halt ? 'until' : 'fire';
        $eventName = sprintf('%s:%s', $this->name(), $eventName);

        return Subbly::events()->{$method}($eventName, $vars);
    }

    /**
     * Get the Api class
     *
     * @return \Subbly\Api\Api
     */
    final protected function api()
    {
        return $this->api;
    }
}
