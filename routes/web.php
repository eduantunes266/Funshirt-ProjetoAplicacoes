<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerImageController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomShirtController;
use App\Http\Controllers\CheckoutController;
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
use App\Http\Controllers\CustomerOrderController;
use Illuminate\Support\Facades\Storage;

Route::get('/', [CatalogController::class, 'index'])->name('home');

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrinho/remover/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth'])->group(function () {
    Route::get('/personalizar', [CustomShirtController::class, 'create'])->name('custom.create');
    Route::post('/personalizar', [CustomShirtController::class, 'store'])->name('custom.store');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/encomenda-sucesso', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/minhas-encomendas', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/minhas-encomendas/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');

    Route::get('/minhas-imagens', [CustomerImageController::class, 'index'])->name('customer.images.index');
    Route::delete('/minhas-imagens/{tshirtImage}', [CustomerImageController::class, 'destroy'])->name('customer.images.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/faturacao', [ProfileController::class, 'updateBilling'])->name('profile.billing.update');

    Route::get('/minhas-imagens/{tshirtImage}/editar', [CustomerImageController::class, 'edit'])->name('customer.images.edit');
Route::put('/minhas-imagens/{tshirtImage}', [CustomerImageController::class, 'update'])->name('customer.images.update');

    Route::get('/imagem-privada/{path}', function ($path) {
        abort_unless(Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path));
    })->where('path', '.*')->name('private.image');
    
});

Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/encomendas', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/encomendas/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/encomendas/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
