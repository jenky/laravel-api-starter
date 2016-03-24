<?php

namespace App\Providers;

use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes($this->app[ApiRouter::class], $router);
        $this->mapWebRoutes($router);
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }

    /**
     * Define the "API" routes for the application.
     *
     * These routes all receive CORS, rate limiting, etc.
     *
     * @param  \Dingo\Api\Routing\Router  $api
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapApiRoutes(ApiRouter $api, Router $router)
    {
        $middleware = ['api'];
        $namespace = $this->namespace.'\\API\\';

        require app_path('Http/api_routes.php');

        $this->mergePackgeRoutes($api, $router);
    }

    /**
     * Merge routes defined by packages to API router.
     *
     * @param  \Dingo\Api\Routing\Router  $api
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mergePackgeRoutes(ApiRouter &$api, Router $router)
    {
        foreach ($router->getRoutes() as $route) {
            $api->version(array_keys($api->getRoutes()), function ($api) use ($route) {
                $action = $route->getAction();

                // Remove prefix if present
                if (isset($action['prefix'])) {
                    unset($action['prefix']);
                }

                $api->addRoute($route->getMethods(), $route->uri(), $action);
            });
        }
    }
}
