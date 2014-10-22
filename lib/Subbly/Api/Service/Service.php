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

    /**
     * Save a Model
     *
     * @param ModelInterface  $model   The Model
     * @param array           $options Options
     *
     * @return Model
     *
     * @throws Subbly\Api\Service\Exception Throw an exception if the model does not use Subbly\Model\Concers\SubblyModel trait
     */
    protected function saveModel(ModelInterface $model, array $options = array())
    {
        if (!in_array('Subbly\\Model\\Concerns\\SubblyModel', class_uses($model)))
        {
            throw new Exception(sprintf('"%s" trait must be use by the model "%s"',
                'Subbly\\Model\\Concerns\\SubblyModel',
                get_class($model)
            ));
        }

        return $model->save(array_replace($options, array(
            'subbly_api_service' => $this,
        )));
    }
}
