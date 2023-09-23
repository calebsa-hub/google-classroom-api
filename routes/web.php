<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleClassroomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/googleLogin', function () {
//     return Socialite::driver('google')->redirect();
// });
Route::get('/googleLogin',
    [GoogleClassroomController::class, 'googleLogin']
);
Route::get('/auth', [GoogleClassroomController::class, 'googleHandle']);

Route::get('/', function () {
    return view('welcome');
})->name('boas-vindas');
//Route::get('/create-classroom', 'GoogleClassroomController@createClassroom');

//$router->post('/courses', [GoogleClassroomController::class, 'createClassroom']);
//$router->get('/courses/{id}', [GoogleClassroomController::class, 'getCourse']);
//$router->get('/courses', [GoogleClassroomController::class, 'listCourses']);
// $router->put('/courses/{id}', [GoogleClassroomController::class, 'updateCourse']);
// $router->patch('/courses/{id}', [GoogleClassroomController::class, 'patchCourse']);
// $router->delete('/courses/{id}', [GoogleClassroomController::class, 'deleteCourse']);
// $router->group(['prefix' => 'v1'], function () use ($router) {
//     $router->get('/courses', [GoogleClassroomController::class, 'createClassroom'])->name('create-class');
//     //$router->get('/courses/{id}', 'GoogleClassroomController@getCourse');
//     //$router->get('/courses', 'GoogleClassroomController@listCourses');
//     //$router->put('/courses/{id}', 'GoogleClassroomController@updateCourse');
//     //$router->patch('/courses/{id}', 'GoogleClassroomController@patchCourse');
// });
//Route::get('/create-classroom', 'GoogleClassroomController@createClassroom');
