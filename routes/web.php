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

// Catalogo publico (acessivel a anonimos).
Route::get('/', [CatalogController::class, 'index'])->name('home');

// Carrinho (acessivel a anonimos - mantido na sessao).
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrinho/remover/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

// Checkout: exclusivo de clientes autenticados (anonimos sao reencaminhados para login).
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/encomenda-sucesso', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Area do cliente autenticado: imagens proprias (G5) e historico de encomendas (G4).
Route::middleware('auth')->group(function () {
    Route::get('minhas-imagens', [CustomImageController::class, 'index'])->name('custom-images.index');
    Route::get('minhas-imagens/criar', [CustomImageController::class, 'create'])->name('custom-images.create');
    Route::post('minhas-imagens', [CustomImageController::class, 'store'])->name('custom-images.store');
    Route::get('minhas-imagens/{customImage}/editar', [CustomImageController::class, 'edit'])->name('custom-images.edit');
    Route::put('minhas-imagens/{customImage}', [CustomImageController::class, 'update'])->name('custom-images.update');
    Route::delete('minhas-imagens/{customImage}', [CustomImageController::class, 'destroy'])->name('custom-images.destroy');

    // Serve o ficheiro privado da imagem (so dono + staff, via TshirtImagePolicy).
    // withTrashed: a imagem pode ter sido removida mas continuar a aparecer em encomendas antigas.
    Route::get('imagens/{tshirtImage}/ficheiro', [CustomImageController::class, 'file'])
        ->name('custom-images.file')
        ->withTrashed();

    Route::get('as-minhas-encomendas', [OrderController::class, 'index'])->name('my-orders.index');
    Route::get('as-minhas-encomendas/{order}', [OrderController::class, 'show'])->name('my-orders.show');
});

// Gestao de encomendas: so funcionarios e administradores.
Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/encomendas', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/encomendas/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/encomendas/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Recibo PDF - acesso restrito pela OrderPolicy (cliente dono ou administrador).
Route::get('/recibos/{order}', [ReceiptController::class, 'download'])
    ->middleware('auth')
    ->name('receipts.download');

// Dashboard generico do Breeze.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil do utilizador autenticado.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/faturacao', [ProfileController::class, 'updateBilling'])->name('profile.billing.update');
});

// Area de administracao (G1 + G2 + G8).
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // G8 - Painel de estatisticas.
    Route::get('painel', [DashboardController::class, 'index'])->name('dashboard');

    // G1 - Funcionarios e administradores.
    Route::resource('staff', StaffController::class)
        ->parameters(['staff' => 'staff'])
        ->except(['show']);

    // G1 - Clientes.
    Route::get('clientes', [CustomerController::class, 'index'])->name('customers.index');
    Route::delete('clientes/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // G1 - Bloquear / desbloquear qualquer conta.
    Route::patch('contas/{user}/bloqueio', AccountStatusController::class)->name('accounts.toggle-block');

    // G2 - Gestao de catalogo.
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('colors', ColorController::class)
        ->parameters(['colors' => 'color:code'])
        ->except(['show']);
    Route::resource('tshirts', TshirtController::class)->except(['show']);

    // G2 - Configuracao de precos (linha unica).
    Route::get('prices', [PriceController::class, 'edit'])->name('prices.edit');
    Route::put('prices', [PriceController::class, 'update'])->name('prices.update');
});

require __DIR__.'/auth.php';
