<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

use Subbly\Api\Service\Service;

abstract class Model extends Eloquent
{
    protected $defaultAttributes = array();

    protected $rules = array();

    private $errors        = array();
    private $errorMessages = array();

    /**
     * The constructor.
     */
    public function __construct(array $attributes = array())
    {
        $attributes = array_replace_recursive($this->defaultAttributes, $attributes);

        parent::__construct($attributes);
    }

    /**
     *
     */
    public function isValid()
    {
        $rules = array();

        foreach ($this->rules as $k=>$v) {
            $rules[$k] = str_replace(array(
                '{self_id}',
            ), array(
                $this->attributes['id'],
            ), $v);
        }

        $v = Validator::make($this->attributes, $rules);

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

        $this->processValidation();

        return parent::save($options);
    }

    /**
     *
     */
    final public function update(array $attributes = array())
    {
        $this->protectMethod($attributes);

        $this->processValidation();

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

    /**
     *
     */
    private function processValidation()
    {
        if (!$this->isValid()) {
            throw new Exception\UnvalidModelException($this->errorMessages()->first());
        }
    }
}
