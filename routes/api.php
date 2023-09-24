<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleClassroomController;
use Laravel\Socialite\Facades\Socialite;

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

// Route::get('/googleLogin',
//     [GoogleClassroomController::class, 'googleLogin']
// );

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('google')->user();

//     // OAuth 2.0 providers...
//     $token = $user->token;
//     $refreshToken = $user->refreshToken;
//     $expiresIn = $user->expiresIn;
// });
//$router->get('/googleLogin', [GoogleClassroomController::class, 'googleLogin']);
//$router->get('/auth', [GoogleClassroomController::class, 'googleHandle']);
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/googleLogin', [GoogleClassroomController::class, 'googleLogin']);
    $router->get('/auth', [GoogleClassroomController::class, 'googleHandle']);
    $router->post('/courses', [GoogleClassroomController::class, 'createClassroom']);
    $router->get('/courses/{id}', [GoogleClassroomController::class, 'getCourse']);
    $router->get('/courses', [GoogleClassroomController::class, 'listCourses']);
    $router->put('/courses/{id}', [GoogleClassroomController::class, 'updateCourse']);
    $router->patch('/courses/{id}', [GoogleClassroomController::class, 'patchCourse']);
    $router->delete('/courses/{id}', [GoogleClassroomController::class, 'deleteCourse']);
});
