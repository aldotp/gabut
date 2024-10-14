<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Response\ResponseJson;

class VerifyAdmin
{
    private $response;

    public function __construct(ResponseJson $response)
    {
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $requiredRole
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Retrieve the authenticated user (already authenticated by VerifyBearerToken middleware)
        $user = JWTAuth::parseToken()->authenticate();

        // Check if the user has the required role
        if ($user->role_id != 1) {
            return $this->response->responseError('Unauthorized role', 403);
        }

        // If the user has the correct role, proceed with the request
        return $next($request);
    }
}
