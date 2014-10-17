<?php

namespace Subbly\Api;

use Subbly\Core\Model;

abstract class Service
{
    /** @var Subbly\Api\Api $api **/
    private $api;

    /**
     * The constructor.
     *
     * @param Subbly\Api\Api  $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
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
     *
     * @return Subbly\Api\Api
     */
    protected function api()
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
