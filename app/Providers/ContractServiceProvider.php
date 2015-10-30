<?php

namespace App\Providers;

use App\Contracts\Repositories\UserRepository as UserRepositoryContract;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class ContractServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $services = [
            UserRepositoryContract::class => UserRepository::class,
        ];

        foreach ($services as $key => $value) {
            $this->app->bindIf($key, $value);
        }
    }
}
