<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class JsonNormalizeSerializer extends ArraySerializer
{
    /**
     * {@inheritdoc}
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
