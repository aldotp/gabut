<?php

use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Link\LinkController;
use App\Http\Controllers\Todo\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyBearerToken;
use App\Http\Middleware\VerifyAdmin;

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => [VerifyBearerToken::class, VerifyAdmin::class]], function() {
        Route::post('/users', [UserController::class, 'createUser']);
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
        Route::put('/users/{id}', [UserController::class, 'updateUser']);
        Route::get('/users/{id}', [UserController::class, 'getUser']);
    });
});

Route::group(['middleware' => VerifyBearerToken::class], function() {
    // Links
    Route::post('/links', [LinkController::class, 'createLink']);
    Route::delete('/links/{id}', [LinkController::class, 'deleteLink']);
    Route::put('/links/{id}', [LinkController::class, 'updateLink']);
    Route::get('/links/{id}', [LinkController::class, 'getLink']);
    Route::get('/links', [LinkController::class, 'getLinksByUserID']);

    Route::post('/todos', [TodoController::class, 'createTodo']);
    Route::delete('/todos/{id}', [TodoController::class, 'deleteTodo']);
    Route::put('/todos/{id}', [TodoController::class, 'updateTodo']);
    Route::get('/todos/{id}', [TodoController::class, 'getTodo']);
    Route::get('/todos', [TodoController::class, 'getTodosByUserID']);
    Route::put('/todos/complete/{id}', [TodoController::class, 'completeTodo']);
});

Route::prefix('auth')->group(function () {
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login']);

    Route::group(['middleware' => VerifyBearerToken::class], function() {
        Route::get("/profile", [AuthController::class, 'profile']);
        Route::delete("/logout", [AuthController::class, 'logout']);
        Route::post("/refresh", [AuthController::class, 'refreshToken']);
    });
});