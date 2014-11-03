<?php

namespace Subbly\Model\Concerns;

trait DefaultValues
{
    /**
     * Override Eloquent method
     *
     * @return void
     */
    public function bootIfNotBooted()
    {
        if (
            isset($this->defaultValues)
            || is_array($this->defaultValues)
            || !empty($this->defaultValues)
        ) {
            $attributes = array_replace_recursive($this->defaultValues, $this->attributes);

            $this->fill($attributes);
        }

        parent::bootIfNotBooted();
    }
}
