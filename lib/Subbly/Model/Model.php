<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

use Subbly\Api\Service\Service;

abstract class Model extends Eloquent
{
    protected $rules = array();

    private $errors        = array();
    private $errorMessages = array();

    /**
     *
     */
    public function validate()
    {
        $v = Validator::make($this->attributes, $this->rules);

        if ($v->fails())
        {
            $this->errors        = $v->errors();
            $this->errorMessages = $v->messages();
            return false;
        }

        return true;
    }

    /**
     *
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     *
     */
    public function errorMessages()
    {
        return $this->errorMessages;
    }

    /**
     *
     */
    final public function save(array $options = array())
    {
        $this->protectMethod($options);

        return parent::save($options);
    }

    /**
     *
     */
    final public function update(array $attributes = array())
    {
        $this->protectMethod($attributes);

        return parent::update($attributes);
    }

    /**
     * TODO protect some others methods like delete, push, ...
     */

    /**
     *
     */
    private function protectMethod(array $options = array())
    {
        // var_dump($options);
        if (
            !isset($options['subbly_api_service'])
            || !($options['subbly_api_service'] instanceof Service)
        ) {
            throw new \Exception('You must use an Subbly\Api\Service\Service to save a Model');
        }

        unset($options['subbly_api_service']);
    }
}
