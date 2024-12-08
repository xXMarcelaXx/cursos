<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginApiController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UserController;
/*
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginApiController::class, 'login']);

Route::post('/usuarios', [UserController::class, 'post']);
Route::get('/usuarios', [UserController::class, 'users']);
Route::delete('/usuarios/{id}', [UserController::class, 'delete']);
Route::get('/usuarios/{id}', [UserController::class, 'show']);
Route::put('/usuarios/{id}', [UserController::class, 'put']);


Route::get('/cursos', [CursoController::class, 'verCursos']);
Route::get('/cursos/{id}', [CursoController::class, 'show']);
Route::get('/cursos/clientes/{id}', [CursoController::class, 'show2']);
Route::post('/cursos', [CursoController::class, 'post']);
Route::delete('/cursos/{id}', [CursoController::class, 'delete']);
Route::put('/cursos/{id}', [CursoController::class, 'put']);

Route::get('/clientes', [ClienteController::class, 'clientes']);
Route::get('/clientes/{id}', [ClienteController::class, 'show']);
Route::post('/clientes', [ClienteController::class, 'post']);
Route::delete('/clientes/{id}', [ClienteController::class, 'delete']);
Route::put('/clientes/{id}', [ClienteController::class, 'put']);