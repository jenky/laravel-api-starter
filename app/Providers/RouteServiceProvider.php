<?php

namespace App\Providers;

use Barryvdh\Debugbar\LaravelDebugbar;
use Barryvdh\Debugbar\Middleware\Debugbar;
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
        $this->mapWebRoutes($router);
        $this->mapApiRoutes($this->app[ApiRouter::class]);
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
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapApiRoutes(ApiRouter $api)
    {
        $middleware = ['api'];

        // Seems like this didn't work cuz HTML response
        // is missing all boilerplates.
        // if ($this->isDebugbarEnabled()) {
        //     $middleware[] = Debugbar::class;
        // }

        $namespace = $this->namespace.'\\API\\';

        require app_path('Http/api_routes.php');
    }

    /**
     * Check if debugbar is enabled.
     *
     * @return bool
     */
    protected function isDebugbarEnabled()
    {
        return class_exists(LaravelDebugbar::class) && ! is_null($this->app[LaravelDebugbar::class]);
    }
}
