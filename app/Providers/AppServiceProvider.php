<?php

namespace App\Providers;

use Exception;
use App\Exceptions\ApiCustomException;
use Dingo\Api\Exception\Handler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerApiCustomError();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the api custom exception.
     * 
     * @return \Illuminate\Http\Response
     */
    protected function registerApiCustomError()
    {
        $this->app[Handler::class]->register(function (ApiCustomException $exception) {

            $response = $this->getDefaultExceptionResponse($exception);

            if ($exception->hasErrors()) {
                $response += $exception->getErrors();
            }

            return response($response, $exception->getStatusCode());
        });
    }

    /**
     * Get the default exception response format.
     * 
     * @param \Exception $exception
     * 
     * @return array
     */
    protected function getDefaultExceptionResponse(Exception $exception)
    {
        $response = [
            'message'     => $exception->getMessage(),
            'status_code' => $exception->getStatusCode(),
        ];

        if (config('api.debug') || config('app.debug')) {
            $response['debug'] = [
                'line'  => $exception->getLine(),
                'file'  => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        return $response;
    }
}
