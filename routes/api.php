<?php

use App\Http\Controllers\MatchController;
use App\Http\Controllers\AuthController;

use App\Models\Match;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListConversationAndMessages;
use App\Http\Controllers\TagController;
use App\Http\Controllers\GraphicsController;
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

Route::get('/all-users', [AuthController::class, 'allUsers']);
Route::get('/view/{id}', [AuthController::class, 'viewUser']);

Route::get('/me', [AuthController::class, 'findByToken']);
Route::get('/tags', [TagController::class, 'show']);

Route::post('/update-profile/me/{user}', [AuthController::class, 'updateProfile']);

Route::resource('matches', MatchController::class);
Route::get('/matches/i-liked/all', [MatchController::class, 'search']);
Route::get('/matches/who-likes-me/all', [MatchController::class, 'searchSecond']);
Route::get('/matches/matched/{target_id}/{status}', [MatchController::class, 'theMatched']);
Route::get('/matches/matched/change/{target_id}/{status}', [MatchController::class, 'changeStatus']);
Route::get('/matches/like/all', [MatchController::class, 'like']);
Route::get('/matches/my-matches/all', [MatchController::class, 'showMatched']);

Route::get('/messages', [ListConversationAndMessages::class, 'render']);
Route::get('/messages/view/', [ListConversationAndMessages::class, 'viewMessage']);
Route::get('/messages/send/', [ListConversationAndMessages::class, 'sendMessage']);
Route::get('/messages/create-new-chat/', [ListConversationAndMessages::class, 'createChat']);

Route::get('/graphics/getGenders/', [GraphicsController::class, 'getGenders']);
Route::get('/graphics/age/', [GraphicsController::class, 'getAge']);
Route::get('/graphics/getOne/', [GraphicsController::class, 'getOne']);
Route::get('/graphics/getZero/', [GraphicsController::class, 'getZero']);


//protected routes
Route::group(['middleware' => ['auth:sanctum']],function () {
    Route::post('/logout', [AuthController::class, 'logout']);


});

//Route::get('/matches', [\App\Http\Controllers\MatchController::class, 'index']);
//
//Route::post('/matches', [\App\Http\Controllers\MatchController::class, 'store']);
//
//Route::get('/matches', [\App\Http\Controllers\MatchController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
