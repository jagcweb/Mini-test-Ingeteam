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

Auth::routes();


//TaskController
Route::get('/', [App\Http\Controllers\TaskController::class, 'getTasks'])->name('home');
Route::get('/create-task', [App\Http\Controllers\TaskController::class, 'create'])->name('task.create');
Route::post('/save-task', [App\Http\Controllers\TaskController::class, 'save'])->name('task.save');
Route::get('/edit-task/{id}', [App\Http\Controllers\TaskController::class, 'edit'])->name('task.edit');
Route::post('/update-task/{id}', [App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
Route::get('/delete-task/{id}', [App\Http\Controllers\TaskController::class, 'delete'])->name('task.delete');