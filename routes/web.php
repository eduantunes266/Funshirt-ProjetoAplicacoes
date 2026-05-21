<?php

use App\Http\Controllers\CustomShirtController;



use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/personalizar', [CustomShirtController::class, 'create'])->name('custom.create');
Route::post('/personalizar', [CustomShirtController::class, 'store'])->name('custom.store');

Route::get('/', [CatalogController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/dashboard', function () {
    return 'Bem-vindo ao painel de administração da FunShirt!';
})->middleware(['auth', 'admin']);

use App\Http\Controllers\CartController;

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');



Route::post('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
require __DIR__.'/auth.php';

Route::post('/personalizar', [CustomShirtController::class, 'store'])->name('custom.store');

use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/encomenda-sucesso', [CheckoutController::class, 'success'])->name('checkout.success');


use App\Http\Controllers\OrderManagementController;

Route::get('/encomendas', [OrderManagementController::class, 'index'])->name('orders.index');

Route::get('/encomendas/{id}', [OrderManagementController::class, 'show'])->name('orders.show');

Route::put('/encomendas/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');

use App\Http\Controllers\DashboardController;

Route::get('/painel', [DashboardController::class, 'index'])->name('admin.dashboard');