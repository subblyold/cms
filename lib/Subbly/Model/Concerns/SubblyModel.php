<?php

namespace Subbly\Model\Concerns;

use Subbly\Api\Service\Service;
use Subbly\Model\Exception\UnvalidModelException;

trait SubblyModel
{
    use DefaultValues;
    use Validable;

    private $callerService;

    /**
     *
     */
    final public function save(array $options = array())
    {
        $this->protectMethod();

        $this->processValidation();

        return parent::save($options);
    }

    /**
     *
     */
    final public function update(array $attributes = array())
    {
        $this->protectMethod();

        $this->processValidation();

        return parent::update($attributes);
    }

    /**
     *
     */
    final public function delete()
    {
        $this->protectMethod();

        return parent::delete();
    }

    /**
     * TODO protect some others methods like delete, push, ...
     */

    /**
     *
     */
    final public function setCaller(Service $service)
    {
        $this->callerService = $service;
    }

    /**
     *
     */
    private function protectMethod()
    {
        if (! ($this->callerService instanceof Service)) {
            throw new \Exception('You must use an Subbly\Api\Service\Service to save a Model');
        }
    }

    /**
     *
     */
    private function processValidation()
    {
        if ($this->isValid() !== true)
        {
            throw new UnvalidModelException($this);
        }
    }
}
