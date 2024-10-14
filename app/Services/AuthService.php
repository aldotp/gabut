<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login($request){
        $email = $request->email;
        $password = $request->password;

        $user = $this->userRepository->findByEmail($email);

        if(!$user || !password_verify($password, $user->password)) {
            return null;
        }

        $credentials = $request->only('email', 'password');
        if (!$accessToken = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Generate refresh token (JWT)
        $refreshTokenPayload = [
            'sub' => $user->id,  // user ID
            'iat' => time(),      // issued at
            'exp' => time() + (30 * 24 * 60 * 60)  // valid for 30 days
        ];
        $refreshToken = JWT::encode($refreshTokenPayload, env('JWT_SECRET'), 'HS256');

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60  // default 1 jam
        ];
    }



    public function register($request){
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $user = $this->userRepository->findByEmail($email);
        if($user) {
            return [null, 'Email already exists'];
        }

        return [$this->userRepository->createUser([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'role_id' => 2,
        ]), null];
    }

    public function refreshToken($request) {
        $refreshToken = $request->refresh_token;


        $decoded = JWT::decode($refreshToken,   new Key(env('JWT_SECRET'), 'HS256'),);
        $userId = $decoded->sub;  // Ambil user ID dari token
        $expiredAt = $decoded->exp;  // Ambil expired time dari token

        if ($expiredAt < time()) {
            return response()->json(['error' => 'Refresh token expired'], 401);
        }

        $user = $this->userRepository->getUser($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $credentials = $request->only('email', 'password');
        if (!$accessToken = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,  // default 1 jam
        ];
    }
}