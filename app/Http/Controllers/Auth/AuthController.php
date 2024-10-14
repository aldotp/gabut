<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Response\ResponseJson;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

 class AuthController
{
    private $authService;
    private $response;

    public function __construct(ResponseJson $response, AuthService $authService) {
        $this->response = $response;
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->response->responseError($validate->errors()->first(), 400);
        }

        [$data, $message] = $this->authService->register($request);
        if ($message) {
            return $this->response->responseError($message, 400);
        }

        return $this->response->responseSuccess($data, 'Register successfully', 200);
    }
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->response->responseError($validate->errors(), 400);
        }

        $data = $this->authService->login($request);
        if (!$data) {
            return $this->response->responseError('Invalid email or password', 400);
        }
        return $this->response->responseSuccess($data, 'Login successfully', 200);
    }


    public function profile(){
        $user = auth()->user();
        if (!$user) {
            return $this->response->responseError('Unauthorized', 401);
        }

        return $this->response->responseSuccess($user, 'Profile successfully', 200);
    }


    public function logout()
    {
        auth()->logout();
        return $this->response->responseSuccess(null, 'Successfully Logout successfully', 200);
    }

    public function refreshToken(Request $request) {
        $data =  $this->authService->refreshToken($request);
        if (!$data) {
            return $this->response->responseError('Refresh token expired', 401);
        }
        return $this->response->responseSuccess($data, 'Refresh token successfully', 200);
    }

}
