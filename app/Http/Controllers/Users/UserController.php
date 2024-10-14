<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Response\ResponseJson;
use App\Services\UserService;
 class UserController
{
    private $response;
    private $userService;

    public function __construct(ResponseJson $response, UserService $userService) {
        $this->response = $response;
        $this->userService = $userService;
    }
    
    public function createUser(Request $request){
       $user = $this->userService->createUser($request->all());
       if ($user == null){
           return $this->response->responseError("Email already exist", 400);
       }
       return $this->response->responseSuccess($user, 'User created successfully', 201);
    }

    public function getAllUsers(){
        $users = $this->userService->getAllUsers();

        return $this->response->responseSuccess($users, 'User fetched successfully', 201);
    }


    public function deleteUser($id){
        $user = $this->userService->deleteUser($id);
        if (!$user) {
            return $this->response->responseError('User not found', 404);
        } else {
            return $this->response->responseSuccess($user, 'User deleted successfully', 200);
        }
    }

    public function updateUser($id, Request $request){
        $user = $this->userService->updateUser($id, $request->all());
        if (!$user) {
            return $this->response->responseError('User not found', 404);
        } else {
            return $this->response->responseSuccess($user, 'User updated successfully', 200);
        }
    }

    public function getUser($id){
        $user = $this->userService->getUser($id);
        if (!$user) {
            return $this->response->responseError('User not found', 404);
        } else {
            return $this->response->responseSuccess($user, 'User fetched successfully', 200);
        }
    }
}