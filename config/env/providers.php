<?php

/**
 * Environment Name => Providers.
 *
 * @return array
 */

return [
    'local' => [
        Jenky\LaravelApiGenerators\GeneratorsServiceProvider::class,
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
    ],
];
