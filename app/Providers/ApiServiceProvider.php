<?php

namespace App\Providers;

use App\Exceptions\ApiCustomException;
use Dingo\Api\Exception\Handler;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiServiceProvider extends ServiceProvider
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
            $response = $this->generateExceptionResponse($e, $e->getStatusCode());

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
            $message = $this->isApiDebugEnabled()
                ? $e->getMessage()
                : trans('error.resource_not_found', ['resource' => strtolower(class_basename($e->getModel()))]);

            throw new HttpException(404, $message, $e);
        });
    }

    /**
     * Get the default exception response format.
     *
     * @param  \Exception $exception
     * @param  int $statusCode
     * @return array
     */
    protected function generateExceptionResponse(Exception $exception, $statusCode = null)
    {
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $statusCode ?: $exception->getStatusCode();
        }

        $response = [
            'message'     => $exception->getMessage(),
            'status_code' => $statusCode ?: 500,
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
