<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('projects');
});

Route::post('/create', 'App\Http\Controllers\ProjectController@createProject');
Route::delete('/delete', 'App\Http\Controllers\ProjectController@deleteProject');
Route::get('/projects', 'App\Http\Controllers\ProjectController@getAllProjects');
Route::get('/archive', 'App\Http\Controllers\ProjectController@getCompletedProjects');
Route::get('/project', 'App\Http\Controllers\ProjectController@getProject');
Route::get('/hours', 'App\Http\Controllers\ProjectController@getHours');
Route::post('/hours', 'App\Http\Controllers\ProjectController@logHours');
Route::post('/completed', 'App\Http\Controllers\ProjectController@markAsCompleted');
Route::post('/invoice', 'App\Http\Controllers\ProjectController@createInvoice');
Route::get('/invoice', 'App\Http\Controllers\ProjectController@getInvoice');