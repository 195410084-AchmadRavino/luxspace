<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyTransactionController;
use App\Http\Controllers\ProductGalleryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/details/{slug}', [FrontendController::class, 'details'])->name('details');

Route::get('/cities/{province_id}', [FrontendController::class, 'getCities']);
Route::get('/ongkir', [FrontendController::class, 'check_ongkir']);
Route::post('/ongkir', [FrontendController::class, 'check_ongkir']);
Route::get('/update-count', [FrontendController::class, 'updateCount']);

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
        Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
        Route::post('/cart/{id}', [FrontendController::class, 'cartAdd'])->name('cart-add');
        Route::delete('/cart/{id}', [FrontendController::class, 'cartDelete'])->name('cart-delete');
        Route::post('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/success', [FrontendController::class, 'success'])->name('checkout-success');

        Route::get('/dashboard/product/{product}/gallery', [ProductGalleryController::class, 'index'])
                ->name('dashboard.product.gallery.index');

        Route::get('/dashboard/product/{product}/gallery/create', [ProductGalleryController::class, 'create'])
                ->name('dashboard.product.gallery.create');

        Route::post('/dashboard/product/{product}/gallery', [ProductGalleryController::class, 'store'])
                ->name('dashboard.product.gallery.store');

        Route::delete('/dashboard/gallery/{id}', [ProductGalleryController::class, 'destroy'])
                ->name('dashboard.gallery.destroy');



        Route::name('dashboard.')->prefix('dashboard')->group(function () {
                Route::get('/', [DashboardController::class, 'index'])->name('index');

                Route::middleware(['auth:sanctum', 'verified'])->group(function () {
                        Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
                        Route::post('/cart/{id}', [FrontendController::class, 'cartAdd'])->name('cart->add');
                        Route::get('/checkout/success', [FrontendController::class, 'success'])->name('checkout-success');
                        Route::resource('my-transaction', MyTransactionController::class)->only([
                                'index', 'show',
                        ]);
                });


                Route::middleware(['admin'])->group(function () {
                        Route::resource('product', ProductController::class);
                        Route::prefix('dashboard')->middleware(['auth:sanctum', 'verified'])->name('dashboard.')->group(function () {
                                // Rute lain di sini...

                                Route::resource('product.gallery', ProductGalleryController::class)->shallow()->only([
                                        'index', 'create', 'store', 'destroy'
                                ]);
                        });
                        Route::resource('transaction', TransactionController::class)->only([
                                'index', 'show', 'edit', 'update'
                        ]);
                        Route::resource('user', UserController::class)->only([
                                'index', 'edit', 'update', 'destroy'
                        ]);
                });
        });
});
