<?php

namespace App\Providers;

use App\Serializers\JsonNormalizeSerializer;
use Dingo\Api\Http\Response\Factory as ResponseFactory;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->makeItemMacro();
        $this->makeCollectionMacro();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Make collection macro.
     *
     * @return \Illuminate\Http\Response
     */
    protected function makeItemMacro()
    {
        $app = $this->app;
        Response::macro('item', function ($value, TransformerAbstract $transformer, $keys = [], SerializerAbstract $serializer = null) use ($app) {
            $response = $app[ResponseFactory::class];
            $serializer = $serializer ?: new JsonNormalizeSerializer;
            return $response->item($value, $transformer, $keys, function ($resource, $fractal) use ($serializer) {
                $fractal->setSerializer($serializer);
            });
        });
    }

    /**
     * Make collection macro.
     *
     * @return \Illuminate\Http\Response
     */
    protected function makeCollectionMacro()
    {
        $app = $this->app;
        Response::macro('collection', function ($value, TransformerAbstract $transformer, $keys = [], SerializerAbstract $serializer = null) use ($app) {
            $response = $app[ResponseFactory::class];
            $serializer = $serializer ?: new JsonNormalizeSerializer;
            return $response->collection($value, $transformer, $keys, function ($resource, $fractal) use ($serializer) {
                $fractal->setSerializer($serializer);
            });
        });
    }
}
