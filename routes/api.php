<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NearestPostCodeController;
use App\Http\Controllers\AuthController;
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

Route::group(
    ['middleware' => 'api', 'prefix' => 'auth'],
    function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    }
);

Route::group(
    ['middleware' => ['api', 'auth:api']],
    function () {
        Route::apiResource('appointment', AppointmentController::class);
        Route::get('postcodes/nearest', NearestPostCodeController::class);
    }
);
