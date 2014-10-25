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
            isset($this->defaultAttributes)
            || is_array($this->defaultAttributes)
            || !empty($this->defaultAttributes)
        ) {
            $attributes = array_replace_recursive($this->defaultAttributes, $this->attributes);

            $this->fill($attributes);
        }

        parent::bootIfNotBooted();
    }
}
