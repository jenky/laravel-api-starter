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
        $this->buildResponseMacro('item');
        $this->buildResponseMacro('collection');
        $this->buildResponseMacro('paginator');
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
     * Build the response macro.
     *
     * @param  string $name
     * @param  string|null $method
     * @return void
     */
    protected function buildResponseMacro($name, $method = null)
    {
        $method = $method ?: $name;
        $app = $this->app;

        Response::macro($name, function ($value, TransformerAbstract $transformer, $keys = [], SerializerAbstract $serializer = null) use ($app, $method) {
            $response = $app[ResponseFactory::class];
            $serializer = $serializer ?: new JsonNormalizeSerializer;
            
            return $response->{$method}($value, $transformer, $keys, function ($resource, $fractal) use ($app, $serializer) {
                $fractal->setSerializer($serializer);

                $with = $app['config']->get('apihelper.prefix', '');
                $with .= 'with';
                if ($includes = $app['request']->input($with)) {
                    $fractal->parseIncludes($includes);
                }
            });
        });
    }
}
