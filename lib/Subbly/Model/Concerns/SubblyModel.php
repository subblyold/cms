<?php

namespace Subbly\Model\Concerns;

use Subbly\Api\Service\Service;
use Subbly\Model\Exception\UnvalidModelException;

trait SubblyModel
{
    use DefaultValues;
    use Validable;

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
        if ($this->isValid() !== true)
        {
            throw new UnvalidModelException($this);
        }
    }
}
