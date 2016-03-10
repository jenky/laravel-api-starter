<?php

namespace App\Http\Response\Format;

use Dingo\Api\Http\Response\Format\Json as BaseJson;

class Json extends BaseJson
{
    /**
     * {@inheritdoc}
     */
    public function formatEloquentModel($model)
    {
        return $this->encode($model->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function formatEloquentCollection($collection)
    {
        return $this->encode($collection->toArray());
    }
}
