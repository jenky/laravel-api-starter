<?php

namespace App\Transformers;

use Illuminate\Support\Str;

trait Transformable
{
    /**
     * Get all transformation data.
     *
     * @return array
     */
    public function transform()
    {
        $output = [];

        if (! is_array($this->transformable)) {
            return $output;
        }

        foreach ($this->transformable as $key) {
            $value = $this->transformAttribute($key);

            if (in_array($key, $this->getDates())) {
                $value = (string) $value;
            }

            $output[$key] = $value;
        }

        return $output;
    }

    /**
     * Transform an attribute.
     *
     * @param  string $key
     * @return mixed
     */
    protected function transformAttribute($key)
    {
        $method = 'transform'.Str::studly($key).'Attribute';

        if (method_exists($this, $method)) {
            return $this->{$method}($key);
        }

        return $this->{$key};
    }
}
