<?php

use App\Http\Controllers\CustomShirtController;



use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/personalizar', [CustomShirtController::class, 'create'])->name('custom.create');
Route::post('/personalizar', [CustomShirtController::class, 'store'])->name('custom.store');
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrinho/remover/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/', [CatalogController::class, 'index'])->name('home');
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TshirtController;

Route::resource('categories', CategoryController::class);
Route::resource('colors', ColorController::class);
Route::resource('tshirts', TshirtController::class);

Route::get('/prices', [PriceController::class, 'edit'])->name('prices.edit');
Route::put('/prices', [PriceController::class, 'update'])->name('prices.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/faturacao', [ProfileController::class, 'updateBilling'])->name('profile.billing.update');
});

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

use App\Http\Controllers\ReceiptController;

// Recibo PDF - acesso restrito pela OrderPolicy (cliente dono ou administrador)
Route::get('/recibos/{order}', [ReceiptController::class, 'download'])
    ->middleware('auth')
    ->name('receipts.download');

use App\Http\Controllers\DashboardController;

Route::get('/painel', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AccountStatusController;

// Area de administracao: gestao de utilizadores (G1)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Funcionarios e administradores
    Route::resource('staff', StaffController::class)
        ->parameters(['staff' => 'staff'])
        ->except(['show']);

    // Clientes
    Route::get('clientes', [CustomerController::class, 'index'])->name('customers.index');
    Route::delete('clientes/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Bloquear / desbloquear qualquer conta
    Route::patch('contas/{user}/bloqueio', AccountStatusController::class)->name('accounts.toggle-block');
});