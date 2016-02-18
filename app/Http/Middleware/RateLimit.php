<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Http\InternalRequest;
use Illuminate\Routing\Middleware\ThrottleRequests;

class RateLimit extends ThrottleRequests
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        if ($request instanceof InternalRequest) {
            $key = $this->resolveRequestSignature($request);
            $response = $next($request);

            return $this->addHeaders(
                $response, $maxAttempts,
                $this->calculateRemainingAttempts($key, $maxAttempts)
            );
        }

        return parent::handle($request, $next, $maxAttempts, $decayMinutes);
    }
}
