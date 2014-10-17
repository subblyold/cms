<?php

namespace Subbly\Api;

use Subbly\Core\Model;

abstract class Service
{
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    abstract public function name();

    protected function api()
    {
        return $this->api;
    }

    protected function saveModel(Model $model, array $options = array())
    {
        $model->save(array_replace(array(
            'subbly_api_service' => $this,
        ), $options));
    }
}
