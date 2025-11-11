<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;





Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'registerUser']);
// routes/api.php
Route::post('/verify-email', [App\Http\Controllers\Api\AuthController::class, 'verifyEmail']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [App\Http\Controllers\Api\PostController::class, 'createPost']);
    

});



Route::get('/foo', function () {
    Artisan::call('storage:link');
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::middleware('auth:sanctum')->group(function () {





//     Route::prefix('user')->group(function () {
//     Route::post('/save-username', [App\Http\Controllers\Api\UserController::class, 'saveUsername']);
//     Route::post('/upload-avatar', [App\Http\Controllers\Api\UserController::class, 'uploadAvatar']);
//     Route::post('/update-user', [App\Http\Controllers\Api\UserController::class, 'updateUser']);
//     Route::post('/', [App\Http\Controllers\Api\UserController::class, 'getProfile']);
//       Route::post('/{id}/posts', [App\Http\Controllers\Api\UserController::class, 'getUserPosts']);

//        Route::post('/{id}/rexers', [App\Http\Controllers\Api\UserController::class, 'getRexers']);


    
    
//     });

//         Route::prefix('rex')->group(function () {

//         Route::post('/toggle', [App\Http\Controllers\Api\RexController::class, 'toggleRex']);
//         Route::get('/my-rexes', [App\Http\Controllers\Api\RexController::class, 'myRexes']);
//         Route::get('/my-rexers', [App\Http\Controllers\Api\RexController::class, 'myRexers']);

//         });


//          Route::prefix('listing')->group(function () {

        

//         Route::post('/create', [App\Http\Controllers\Api\ListingController::class, 'create']);
//         Route::post('/listings', [App\Http\Controllers\Api\ListingController::class, 'getListings']);

//         });



        


    

//     Route::get('/posts', [App\Http\Controllers\Api\PostController::class, 'getPosts']);
//     Route::post('/create-post', [App\Http\Controllers\Api\PostController::class, 'createPost']);
//     Route::post(' /post/{id}', [App\Http\Controllers\Api\PostController::class, 'getSinglePost']);
    


//     Route::get('/posts/{id}/comments', [App\Http\Controllers\Api\CommentController::class, 'getPostComments']);
//     Route::post('/create-comment', [App\Http\Controllers\Api\CommentController::class, 'create']);

//     Route::post('/like', [App\Http\Controllers\Api\LikeController::class, 'toggleLike']);







// });




// Route::prefix('auth')->group(function () {
//     Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
//     Route::post('login',    [App\Http\Controllers\Api\AuthController::class, 'login']);
//     Route::post('forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
//     Route::post('change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword']);
    
// });
