<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register all routes for API system.
|
*/

$api = app(Dingo\Api\Routing\Router::class);
$namespace = app()->getNamespace().'Http\\Controllers\\API\\';
$middleware = ['api'];

if (! is_null(app(Barryvdh\Debugbar\LaravelDebugbar::class))) {
    $middleware[] = Barryvdh\Debugbar\Middleware\Debugbar::class;
}

$api->version('v1', ['middleware' => $middleware], function ($api) use ($namespace) {
    $api->group(['namespace' => $namespace.'v1'], function ($api) {
        $api->resources([
            'users' => 'UsersController',
        ]);
    });
});
