<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Lista e filtra os clientes da loja.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'ativos');

        $customers = User::query()
            ->where('user_type', 'C')
            ->when($status === 'removidos', fn ($q) => $q->onlyTrashed())
            ->when($status === 'bloqueados', fn ($q) => $q->where('blocked', true))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with('customer:id,nif')
            ->select('id', 'name', 'email', 'blocked', 'photo_url', 'created_at', 'deleted_at')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search', 'status'));
    }

    /**
     * Remove um cliente: soft delete se tiver historico, caso contrario remocao definitiva.
     */
    public function destroy(User $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);
        abort_unless($customer->user_type === 'C', 404);

        $hasHistory = Order::where('customer_id', $customer->id)->exists()
            || TshirtImage::where('customer_id', $customer->id)->exists();

        if ($hasHistory) {
            // Mantem o historico da plataforma intacto.
            $customer->customer?->delete();
            $customer->delete();
            $message = 'Cliente removido (soft delete - tem encomendas ou imagens associadas).';
        } else {
            // Sem historico: apaga primeiro customers e depois users (ordem das chaves estrangeiras).
            $customer->customer?->forceDelete();
            $customer->forceDelete();
            $message = 'Cliente removido definitivamente.';
        }

        return redirect()->route('admin.customers.index')->with('success', $message);
    }
}
