<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Subbly\Api\Service\Service;

abstract class Model extends Eloquent
{
    final public function save(array $options = array())
    {
        if (
            isset($options['subbly_api_service'])
            && $options['subbly_api_service'] instanceof Service
        ) {
            throw new \Exception('You must use an Subbly\Api\Service\Service to save a Model');
        }

        return parent::save($options);
    }
}
