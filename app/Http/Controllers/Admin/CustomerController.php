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
    // Lista todos os clientes
    public function index(Request $request): View
    {
        // Verifica se o utilizador tem permissão para ver clientes
        $this->authorize('viewAny', User::class);

        // Obtém os parâmetros de pesquisa e estado do pedido
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'ativos');

        // Cria a query para ir buscar apenas utilizadores do tipo Cliente
        $customers = User::query()
            ->where('user_type', 'C')
            // Filtra por estado (removidos, bloqueados)
            ->when($status === 'removidos', fn ($q) => $q->onlyTrashed())
            ->when($status === 'bloqueados', fn ($q) => $q->where('blocked', true))
            // Se houver pesquisa, procura pelo nome ou email
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            // Carrega a relação com o cliente (para o nif) e seleciona colunas específicas
            ->with('customer:id,nif')
            ->select('id', 'name', 'email', 'blocked', 'photo_url', 'created_at', 'deleted_at')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search', 'status'));
    }

    // Remove um cliente (ou faz soft delete)
    public function destroy(User $customer): RedirectResponse
    {
        // Verifica as permissões de eliminação
        $this->authorize('delete', $customer);
        abort_unless($customer->user_type === 'C', 404);

        // Verifica se o cliente tem encomendas ou imagens personalizadas
        $hasHistory = Order::where('customer_id', $customer->id)->exists()
            || TshirtImage::where('customer_id', $customer->id)->exists();

        // Se tiver histórico, faz apenas soft delete
        if ($hasHistory) {
            $customer->customer?->delete();
            $customer->delete();
            $message = 'Cliente removido (soft delete - tem encomendas ou imagens associadas).';
        } else {
            // Se não tiver histórico, remove permanentemente (force delete)
            $customer->customer?->forceDelete();
            $customer->forceDelete();
            $message = 'Cliente removido definitivamente.';
        }

        return redirect()->route('admin.customers.index')->with('success', $message);
    }
}
