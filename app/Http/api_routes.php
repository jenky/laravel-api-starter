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
$namespace = 'App\Http\Controllers\API\\';
$middleware = ['cors'];

if (! is_null(app('debugbar'))) {
    $middleware[] = 'Barryvdh\Debugbar\Middleware\Debugbar';
}

$api->version('v1', ['middleware' => $middleware], function ($api) use ($namespace) {
    $api->group(['namespace' => $namespace.'v1'], function ($api) {
        $api->resources([
            'users'   => 'UsersController',
            'tracks'  => 'TracksController',
            'artists' => 'ArtistsController',
        ]);
    });
});