<?php

namespace Subbly\Model;

use Subbly\Api\Service\Service;

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
     * Set the caller Service
     *
     * @param Subbly\api\Service\Service  $service The caller Service
     */
    public function setCaller(Service $service);
}
