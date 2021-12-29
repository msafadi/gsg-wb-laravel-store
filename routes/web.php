<?php

use App\Http\Controllers\Dashbaord\DashboardController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index']);

Route::get('/admin/dashboard', [DashboardController::class, 'index']);
Route::get('/admin/dashboard/page', [DashboardController::class, 'page']);

Route::get('/news/latest', [HomeController::class, 'index']);
//Route::get('/news/{id?}', [HomeController::class, 'news']);

Route::get('/news/{category}', [HomeController::class, 'news']);
Route::get('/news/{category}/{id}', [HomeController::class, 'news']);
