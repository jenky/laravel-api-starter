<?php

namespace App\Providers;

use App\Contracts\Repositories\TrackRepository as TrackRepositoryContract;
use App\Contracts\Repositories\UserRepository as UserRepositoryContract;
use App\Repositories\TrackRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class ContractServiceProvider extends ServiceProvider
{
    /** 
     * The application's contracts.
     * 
     * @var array
     */
    protected $services = [
        UserRepositoryContract::class  => UserRepository::class,
        TrackRepositoryContract::class => TrackRepository::class,
    ];

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
        foreach ($this->services as $key => $value) {
            $this->app->bindIf($key, $value);
        }
    }
}
