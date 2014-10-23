<?php

namespace Subbly\Model;

interface ModelInterface
{

    /**
     * Is model valid
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Get the error messages
     *
     * @return array
     */
    public function errorMessages();

    /**
     * 
     */
    final public function setCaller(Service $service);
}
