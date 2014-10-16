<?php

namespace Subbly\Api;

user Subbly\Core\Model;

abstract class Service
{
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    protected function api()
    {
        return $this->api;
    }

    protected function saveModel(Model $model)
    {
        $model->save();
    }
}
