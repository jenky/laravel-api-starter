<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class RawTransformer extends TransformerAbstract
{
    /**
     * Transform the model.
     *
     * @return array
     */
    public function transform(Model $model)
    {
        return $model->toArray();
    }
}
