<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    public function save(array $options = array())
    {
        if (
            isset($options['subbly_api_service'])
            && $options['subbly_api_service'] instanceof Service
        ) {
            throw new \Exception('You must use the Subbly\Api to save a Model');
        }

        return parent::save($options);
    }
}
