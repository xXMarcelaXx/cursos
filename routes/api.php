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
Route::middleware('auth:sanctum')->get('/logout', [LoginApiController::class, 'logout']);

Route::get('/cursos', [CursoController::class, 'verCursos']);
Route::post('/cursos', [CursoController::class, 'post']);

Route::get('/clientes', [ClienteController::class, 'clientes']);
Route::post('/clientes', [ClienteController::class, 'post']);