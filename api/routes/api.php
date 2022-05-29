<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [\App\Http\Controllers\Auth\JWTAuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(\App\Http\Controllers\UserController::class)->group(function (){
        Route::get('/users/all', 'getAllUsers');
        Route::post('/users/store', 'createNewUser');
        Route::put('/users/update/{id}', 'updateUser');
        Route::delete('/users/delete/{id}', 'deleteUser');
    });

    Route::controller(\App\Http\Controllers\TaskController::class)->group(function (){
        Route::get('/tasks/all', 'getAllTasks');
        Route::get('/tasks/find/user/{userId}', 'getAllTasksByUser');
        Route::get('/tasks/show/{id}', 'findTaskById');
        Route::post('/tasks/store', 'createNewTask');
        Route::put('/tasks/update/{id}', 'updateTask');
        Route::delete('/tasks/delete/{id}', 'deleteTask');
    });

    Route::post('/logout', [\App\Http\Controllers\Auth\JWTAuthController::class, 'logout']);
});
