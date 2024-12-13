<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\DirectoryController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CheckListController;
use App\Http\Controllers\Api\CheckListChildController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\ProjectController;
use App\Models\Product;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('forgot-password', [AuthController::class, 'forgot']);
    Route::post('reset-password', [AuthController::class, 'reset']);
});

Route::group(['middleware' => ['api', 'jwt.auth']], function () {
    Route::group(['prefix' => '/directories'], function () {
        Route::get('/',[DirectoryController::class,'index']);
        Route::post('/',[DirectoryController::class,'store']);
        Route::put('/{id}',[DirectoryController::class,'update']);
        Route::delete('/{id}',[DirectoryController::class,'destroy']);
        Route::put('/{id}/index',[DirectoryController::class,'changeIndex']);
    });

    Route::group(['prefix' => '/labels'], function () {
        Route::get('/',[LabelController::class,'index']);
        Route::put('/{id}',[LabelController::class,'update']);
        Route::delete('/{id}',[LabelController::class,'destroy']);
    });

    Route::group(['prefix' => '/files'], function () {
        Route::put('/{id}', [FileController::class, 'update']);
        Route::delete('/{id}', [FileController::class, 'destroy']);
    });

    Route::group(['prefix' => '/cards'], function () {
        Route::post('/',[CardController::class,'store']);
        Route::put('/{id}',[CardController::class,'update']);
        Route::delete('/{id}',[CardController::class,'destroy']);
        Route::put('/{id}/directory',[CardController::class,'changeDirectory']);
        Route::put('/{id}/index',[CardController::class,'changeIndex']);
        Route::get('/{id}',[CardController::class,'show']);
        Route::post('/{id}/upload-file', [CardController::class, 'uploadFile']);
        Route::put('/{id}/change-status',[CardController::class,'changeStatusCompleted']);
        Route::put('/{id}/change-status-deadline',[CardController::class,'changeStatusDeadline']);
        Route::post('/{id}/attach-labels', [CardController::class, 'attachExistLabel']);
        Route::delete('/{id}/detach-labels', [CardController::class, 'detachLabel']);
        Route::post('/{id}/label', [CardController::class, 'attachNewLabelWithCard']);
    });

    Route::group(['prefix' => '/check-lists'], function () {
        Route::post('/',[CheckListController::class,'store']);
        Route::put('/{id}',[CheckListController::class,'update']);
        Route::delete('/{id}',[CheckListController::class,'destroy']);
    });

    Route::group(['prefix' => '/check-list-childs'], function () {
        Route::post('/',[CheckListChildController::class,'store']);
        Route::put('/{id}',[CheckListChildController::class,'update']);
        Route::delete('/{id}',[CheckListChildController::class,'destroy']);
        Route::put('/{id}/change-status',[CheckListChildController::class,'changeStatus']);
    });
    
    Route::group(['prefix' => '/users'], function () {
        Route::post('/', [UserController::class, 'update']);
        Route::put('/password', [UserController::class, 'changePassword']);
    });
});

Route::group(['prefix' => '/todos'], function () {
    Route::get('/',[TodoController::class,'index']);
    Route::post('/',[TodoController::class,'store']);
    Route::put('/{id}',[TodoController::class,'update']);
    Route::delete('/{id}',[TodoController::class,'destroy']);
});

Route::group(['prefix' => '/products'], function () {
    Route::get('/',[ProductController::class,'index']);
    Route::post('/',[ProductController::class,'store']);
    Route::post('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
});
Route::group(['prefix' => '/users'], function () {
    Route::get('/',[UserController::class,'listUser']);
    Route::get('/project/{id}',[UserController::class,'listUserByProject']);
});

Route::group(['prefix' => '/projects'], function () {
    Route::get('/',[ProjectController::class,'index']);
    Route::post('/',[ProjectController::class,'store']);
    Route::delete('/{id}',[ProjectController::class,'destroy']);
    Route::get('/cards/{id}',[ProjectController::class,'getCards']);
});