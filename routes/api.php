<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\ResultController;
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

Route::group(['prefix' => 'poll'], function () {
    Route::get('/{id}', [PollController::class, 'show']);
    Route::get('/{id}/result', [ResultController::class, 'show']);
    Route::post('/{id}/result/create', [ResultController::class, 'store']);
    Route::post('/create', [PollController::class, 'store']);
});
