<?php

use App\Http\Controllers\Auth\ChangeUserPasswordController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController as StoreProductsController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{category:slug?}', [StoreProductsController::class, 'index'])
    ->name('products');
Route::get('/products/{category:slug}/{product:slug}', [StoreProductsController::class, 'show'])
    ->name('products.show');

Route::group([
    'prefix' => '/dashboard',
    'as' => 'dashboard.',
    'namespace' => 'Dashboard',
    'middleware' => ['auth'],
], function() {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/products/trash', [ProductsController::class, 'trash'])
        ->name('products.trash');
    Route::patch('/products/{product}/restore', [ProductsController::class, 'restore'])
        ->name('products.restore');
    Route::resource('/products', 'ProductsController')->names([
        'index' => 'products.index',
        'show' => 'products.show',
    ]);

    Route::prefix('/categories')->as('categories.')->group(function() {
        // CRUD: Create, Read, Update and Delete
        Route::get('/', 'CategoriesController@index')
            ->name('index'); //dashboard.categories.index

        Route::get('/trash', [CategoriesController::class, 'trash'])
            ->name('trash');

        Route::get('/create', [CategoriesController::class, 'create'])
            ->name('create');

        Route::post('/', [CategoriesController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [CategoriesController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [CategoriesController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [CategoriesController::class, 'destroy'])
            ->name('destroy');

        Route::patch('/{id}/restore', [CategoriesController::class, 'restore'])
            ->name('restore');

    });
});

Route::get('/profile', [UserProfileController::class, 'index'])
    ->name('profile')
    ->middleware(['auth:web,admin', 'password.confirm']);
Route::patch('/profile', [UserProfileController::class, 'update'])
    ->name('profile.update')
    ->middleware(['auth:web,admin', 'password.confirm']);

Route::get('/change-password', [ChangeUserPasswordController::class, 'index'])
    ->name('change-password')
    ->middleware(['auth:web,admin']);
Route::put('/change-password', [ChangeUserPasswordController::class, 'update'])
    ->name('change-password.update')
    ->middleware(['auth:web,admin']);

Route::get('/dashboard/breeze', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
