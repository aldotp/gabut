<?php

namespace App\Http\Middleware;

use App\Response\ResponseJson;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;

class VerifyBearerToken
{


/**
* Handle an incoming request.
*
* @param \Illuminate\Http\Request $request
* @param \Closure $next
* @return mixed
*/

private $response;

public function __construct(ResponseJson $response){
    $this->response = $response;

}

public function handle(Request $request, Closure $next)
{
    try {
    // Coba ambil token dari request dan verifikasi
    JWTAuth::parseToken()->authenticate();
    } catch (TokenExpiredException $e) {
        return $this->response->responseError('Token has expired', 401);
    } catch (TokenInvalidException $e) {
    // Token tidak valid
        return $this->response->responseError('Invalid token', 401);
    } catch (Exception $e) {
    // Tidak ada token
        return $this->response->responseError('Token not found',404);
    }

    // Jika token valid, lanjutkan ke request berikutnya
    return $next($request);
    }
}
