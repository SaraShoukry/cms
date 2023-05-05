<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group(['prefix' => 'operators'], function ($router) {
    $router->get('/', [\App\Http\Controllers\OperatorController::class, 'index']);
    $router->get('/{id}', [\App\Http\Controllers\OperatorController::class, 'show']);
    $router->post('', [\App\Http\Controllers\OperatorController::class, 'store']);
    $router->post('/{id}/edit', [\App\Http\Controllers\OperatorController::class, 'update']);
    $router->delete('/', [\App\Http\Controllers\OperatorController::class, 'delete']);
});
Route::group(['prefix' => 'students'], function ($router) {
    $router->get('/', [\App\Http\Controllers\StudentController::class, 'index']);
    $router->get('/{id}', [\App\Http\Controllers\StudentController::class, 'show']);
    $router->post('', [\App\Http\Controllers\StudentController::class, 'store']);
    $router->post('/edit', [\App\Http\Controllers\StudentController::class, 'update']);
    $router->delete('/{id}', [\App\Http\Controllers\StudentController::class, 'delete']);
});
