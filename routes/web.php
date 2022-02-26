<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Auth\ChangeUserPasswordController;
use App\Http\Controllers\Dashboard\NotificationsController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\ProductsController as StoreProductsController;

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

Route::post('/products/{product}/reviews', [StoreProductsController::class, 'review'])
    ->name('products.reviews.store');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart', [CartController::class, 'store']);
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store']);

Route::group([
    'prefix' => '/dashboard',
    'as' => 'dashboard.',
    'namespace' => 'Dashboard',
    'middleware' => ['auth'],
], function() {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('index');
    
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::get('notifications/{notification}', [NotificationsController::class, 'read'])->name('notifications.read');
    

    Route::get('/products/trash', [ProductsController::class, 'trash'])
        ->name('products.trash');
    Route::patch('/products/{product}/restore', [ProductsController::class, 'restore'])
        ->name('products.restore');

    Route::resource('/products', 'ProductsController')->names([
        'index' => 'products.index',
        'show' => 'products.show',
    ]);

    Route::resources([
        'roles' => 'RolesController',
        'users' => 'UsersController',
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


Route::get('/images/{image}', function($image) {

    //$path = storage_path('app/dummy.txt');
    if (!Storage::exists($image)) {
        abort(404);
    }
    return Response::file(Storage::path($image));

    //return Storage::download('dummy.txt');
});