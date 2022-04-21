<?php

use App\Http\Controllers\MatchController;
use App\Http\Controllers\AuthController;

use App\Models\Match;
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

Route::post('/register', [AuthController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);

//protected routes
Route::group(['middleware' => ['auth:sanctum']],function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/update-profile/{id}', [AuthController::class, 'updateProfile']);

    Route::resource('matches', MatchController::class);
    Route::get('/matches/i-liked/{user_id}', [MatchController::class, 'search']);
    Route::get('/matches/who-likes-me/{user_id}', [MatchController::class, 'searchSecond']);
    Route::get('/matches/matched/{user_id}/{target_id}/{status}', [MatchController::class, 'theMatched']);
});

//Route::get('/matches', [\App\Http\Controllers\MatchController::class, 'index']);
//
//Route::post('/matches', [\App\Http\Controllers\MatchController::class, 'store']);
//
//Route::get('/matches', [\App\Http\Controllers\MatchController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
