<?php

namespace App\Http\Controllers\Todo;

use Illuminate\Http\Request;
use App\Response\ResponseJson;
use App\Services\TodoService;

 class TodoController
{
    private $response;
    private $todoService;


    public function __construct(ResponseJson $response, TodoService $todoService) {
        $this->response = $response;
        $this->todoService = $todoService;
    }
    public function createTodo(Request $request){
       $user = $this->todoService->createTodo($request->all());
       if ($user == null){
           return $this->response->responseError("Todo already exist", 400);
       }
       return $this->response->responseSuccess($user, 'Todo created successfully', 201);
    }

    public function deleteTodo($id){
        $user = $this->todoService->deleteTodo($id);
        if (!$user) {
            return $this->response->responseError('Todo not found', 404);
        } else {
            return $this->response->responseSuccess($user, 'Todo deleted successfully', 200);
        }
    }

    public function updateTodo($id, Request $request){
        $user = $this->todoService->updateTodo($id, $request->all());
        if (!$user) {
            return $this->response->responseError('Todo not found', 404);
        } else {
            return $this->response->responseSuccess($user, 'Todo updated successfully', 200);
        }
    }

    public function getTodo($id){
        $todo = $this->todoService->getTodo($id);
        if (!$todo) {
            return $this->response->responseError('Todo not found', 404);
        } else {
            return $this->response->responseSuccess($todo, 'Todo fetched successfully', 200);
        }
    }

    public function getTodosByUserID (Request $request){

        $filter = $request->query('filter');

        $todos = $this->todoService->getTodosByUserID($filter);
        if (!$todos) {
            return $this->response->responseError('Todo not found', 404);
        } else {
            return $this->response->responseSuccess($todos, 'Todo fetched successfully', 200);
        }
    }

    public function completeTodo($id){
        $todo = $this->todoService->completeTodo($id);
        if (!$todo) {
            return $this->response->responseError('Todo not found', 404);
        } else {
            return $this->response->responseSuccess(null, 'Todo completed successfully', 200);
        }
    }
}
