<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use JWTAuth;

class JWTCheckMiddleware extends \Tymon\JWTAuth\Middleware\BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        info('middleware');
        //token validates inside
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 401);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', 401);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', 401);
        } catch(Exception $e) {
            return $this->respond('unknown_exception', $e->getMessage(), 401);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 403);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }

}
