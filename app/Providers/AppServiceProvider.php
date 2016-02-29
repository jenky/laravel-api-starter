<?php

namespace App\Providers;

use Exception;
use App\Exceptions\ApiCustomException;
use Dingo\Api\Exception\Handler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApiCustomError();
        $this->registerApiModelNotFoundException();
    }

    /**
     * Register the API custom exception.
     *
     * @return \Illuminate\Http\Response
     */
    protected function registerApiCustomError()
    {
        $this->app[Handler::class]->register(function (ApiCustomException $e) {
            $response = $this->getDefaultExceptionResponse($e);

            if ($e->hasErrors()) {
                $response += $e->getErrors();
            }

            return response($response, $e->getStatusCode());
        });
    }

    /**
     * Register Model not found for API.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function registerApiModelNotFoundException()
    {
        $this->app[Handler::class]->register(function (ModelNotFoundException $e) {
            $message = $this->isApiDebugEnabled() ? $e->getMessage() : trans('error.resource_not_found', ['resource' => strtolower(class_basename($e->getModel()))]);
            throw new HttpException(404, $message, $e);
        });
    }

    /**
     * Get the default exception response format.
     *
     * @param \Exception $exception
     * @return array
     */
    protected function getDefaultExceptionResponse(Exception $exception)
    {
        $response = [
            'message'     => $exception->getMessage(),
            'status_code' => $exception->getStatusCode(),
        ];

        if ($this->isApiDebugEnabled()) {
            $response['debug'] = [
                'line'  => $exception->getLine(),
                'file'  => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        return $response;
    }

    /**
     * Check if API debug mode is enabled.
     *
     * @return bool
     */
    protected function isApiDebugEnabled()
    {
        return config('api.debug');
    }
}
