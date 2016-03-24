<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register all routes for API system.
|
*/

$api->version('v1', ['middleware' => $middleware], function ($api) use ($namespace) {
    $api->group(['namespace' => $namespace.'v1'], function ($api) {
        $api->resources([
            'users' => 'UsersController',
        ]);
    });
});
