<?php

namespace Subbly\Api\Service;

use Subbly\Api\Api;
use Subbly\Model\Model;

abstract class Service
{
    /** @var Subbly\Api\Api $api **/
    private $api;

    /**
     * The constructor.
     *
     * @param Subbly\Api\Api  $api
     */
    final public function __construct(Api $api)
    {
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
    protected function init()
    {
    }

    /**
     *
     * @return Subbly\Api\Api
     */
    final protected function api()
    {
        return $this->api;
    }

    /**
     *
     */
    protected function saveModel(Model $model, array $options = array())
    {
        return $model->save(array_replace(array(
            'subbly_api_service' => $this,
        ), $options));
    }
}
