<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Http\InternalRequest;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            return $next($request)->withHeaders([
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => $maxAttempts - $this->limiter->attempts($key) + 1,
            ]);
        }
        
        $key = $this->resolveRequestSignature($request);
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            throw new HttpException(429, 'Too Many Attempts.', null, [
                'Retry-After' => $this->limiter->availableIn($key),
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        return parent::handle($request, $next, $maxAttempts, $decayMinutes);
    }
}
