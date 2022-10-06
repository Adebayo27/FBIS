<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['api.response']], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register.api');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::match(['post', 'get'], 'logout', [AuthController::class, 'logout'])->name('logout');
    
    
    Route::group(['middleware' => ['api.expiry']], function () { 
        // dd('j');
        Route::post('create-post', [PostController::class, 'createPost'])->name('createPost.api');
        Route::get('post/{id}', [PostController::class, 'getSinglePost'])->name('getSinglePost.api');
        Route::post('post/{id}', [PostController::class, 'updatePost'])->name('updatePost.api');
        Route::get('posts', [PostController::class, 'getPosts'])->name('getPosts.api');
        Route::post('delete-post/{id}', [PostController::class, 'destroyPost'])->name('destroyPost.api');
        Route::get('get-comments/{id}', [PostController::class, 'getComments'])->name('getComments.api');

        

        //comment
        Route::post('post-comment', [CommentController::class, 'createComment'])->name('createComment.api');
        Route::post('delete-comment', [CommentController::class, 'destroyComment'])->name('destroyComment.api');
        // destroyComment
    });
    
    
});
