<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomImageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AccountStatusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\TshirtController;
use App\Http\Controllers\Admin\PriceController;

Route::get('/', [CatalogController::class, 'index'])->name('home');

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrinho/remover/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/encomenda-sucesso', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::middleware('auth')->group(function () {
    Route::get('minhas-imagens', [CustomImageController::class, 'index'])->name('custom-images.index');
    Route::get('minhas-imagens/criar', [CustomImageController::class, 'create'])->name('custom-images.create');
    Route::post('minhas-imagens', [CustomImageController::class, 'store'])->name('custom-images.store');
    Route::get('minhas-imagens/{customImage}/editar', [CustomImageController::class, 'edit'])->name('custom-images.edit');
    Route::put('minhas-imagens/{customImage}', [CustomImageController::class, 'update'])->name('custom-images.update');
    Route::delete('minhas-imagens/{customImage}', [CustomImageController::class, 'destroy'])->name('custom-images.destroy');

    Route::get('imagens/{tshirtImage}/ficheiro', [CustomImageController::class, 'file'])
        ->name('custom-images.file')
        ->withTrashed();

    Route::get('as-minhas-encomendas', [OrderController::class, 'index'])->name('my-orders.index');
    Route::get('as-minhas-encomendas/{order}', [OrderController::class, 'show'])->name('my-orders.show');
});

Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/encomendas', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/encomendas/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/encomendas/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::get('/recibos/{order}', [ReceiptController::class, 'download'])
    ->middleware('auth')
    ->name('receipts.download');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/faturacao', [ProfileController::class, 'updateBilling'])->name('profile.billing.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('painel', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('staff', StaffController::class)
        ->parameters(['staff' => 'staff'])
        ->except(['show']);

    Route::get('clientes', [CustomerController::class, 'index'])->name('customers.index');
    Route::delete('clientes/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::patch('contas/{user}/bloqueio', AccountStatusController::class)->name('accounts.toggle-block');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('colors', ColorController::class)
        ->parameters(['colors' => 'color:code'])
        ->except(['show']);
    Route::resource('tshirts', TshirtController::class)->except(['show']);

    Route::get('prices', [PriceController::class, 'edit'])->name('prices.edit');
    Route::put('prices', [PriceController::class, 'update'])->name('prices.update');
});

require __DIR__.'/auth.php';
