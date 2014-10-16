<?php

namespace Subbly\Core\Model;

abstract class Model extends Eloquent
{
    public function save(array $options = array())
    {
        if (false) {
            throw new \Exception('You must use the Subbly\Api to save a Model');
        }

        return parent::save($options);
    }
}
