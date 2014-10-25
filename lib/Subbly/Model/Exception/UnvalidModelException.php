<?php

namespace Subbly\Model\Exception;

use Subbly\Model\ModelInterface;

class UnvalidModelException extends \Exception
{
    /** @var ModelInterface $model */
    private $model;

    /**
     * The constructor.
     *
     * @param ModelInterface  $model The model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;

        $this->message = sprintf('"%s" model is unvalid. FIRST ERROR MESSAGE: %s',
            get_class($model),
            $this->firstErrorMessage()
        );
    }

    /**
     * Get the model
     *
     * @return ModelInterface
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Return the first unvalid error messages
     *
     * @return string
     */
    public function firstErrorMessage()
    {
        if ($this->model->errorMessages()) {
            return $this->model->errorMessages()->first();
        }

        return '';
    }
}
