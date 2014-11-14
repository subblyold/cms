<?php

namespace Subbly\Validation;

use Symfony\Component\Intl\Intl;

use Illuminate\Validation\Validator;

class CountryValidator extends Validator
{
    /**
     * 
     */
    public function validate($attribute, $value, $parameters)
    {
        $value     = (string) $value;
        $countries = Intl::getRegionBundle()->getCountryNames();

        return isset($countries[$value]);
    }
}
