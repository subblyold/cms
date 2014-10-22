<?php

namespace Subbly\Model\Concerns;

use Illuminate\Support\Facades\Validator;

trait Validable
{
    /** @var array $errorMessages */
    private $errorMessages = array();

    /**
     * Is model valid
     *
     * @return boolean
     */
    public function isValid()
    {
        if (!isset($this->rules) || !is_array($this->rules) || empty($this->rules)) {
            return true;
        }

        $rules        = array();
        $replacements = array();

        foreach ($this->attributes as $k=>$v) {
            $replacements['{{self_'.$k.'}}'] = $v;
        }

        foreach ($this->rules as $k=>$v) {
            $rules[$k] = str_replace(array_keys($replacements), array_values($replacements), $v);
        }

        $v = Validator::make($this->attributes, $rules);

        if ($v->fails())
        {
            $this->errorMessages = $v->messages();
            return false;
        }

        return true;
    }

    /**
     * Get the error messages
     *
     * @return array
     */
    public function errorMessages()
    {
        return $this->errorMessages;
    }
}
