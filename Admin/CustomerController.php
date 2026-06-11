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
     * Lista todos os clientes da plataforma.
     */
    public function index(Request $request): View
    {
        // Verifica se o utilizador tem permissão para visualizar clientes
        $this->authorize('viewAny', User::class);

        // Obtém os filtros enviados pelo formulário
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'ativos');

        // Query para obter apenas utilizadores do tipo cliente
        $customers = User::query()
            ->where('user_type', 'C')

            // Mostra apenas clientes removidos (soft delete)
            ->when(
                $status === 'removidos',
                fn ($q) => $q->onlyTrashed()
            )

            // Mostra apenas clientes bloqueados
            ->when(
                $status === 'bloqueados',
                fn ($q) => $q->where('blocked', true)
            )

            // Pesquisa por nome ou email
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })

            // Carrega os dados de faturação necessários
            ->with('customer:id,nif')

            // Seleciona apenas os campos necessários
            ->select(
                'id',
                'name',
                'email',
                'blocked',
                'photo_url',
                'created_at',
                'deleted_at'
            )

            // Ordena alfabeticamente
            ->orderBy('name')

            // Paginação
            ->paginate(15)

            // Mantém filtros na URL
            ->withQueryString();

        // Envia os dados para a view
        return view(
            'admin.customers.index',
            compact('customers', 'search', 'status')
        );
    }

    /**
     * Remove um cliente.
     */
    public function destroy(User $customer): RedirectResponse
    {
        // Verifica permissões
        $this->authorize('delete', $customer);

        // Garante que é realmente um cliente
        abort_unless($customer->user_type === 'C', 404);

        // Verifica se o cliente tem histórico associado
        $hasHistory =
            Order::where('customer_id', $customer->id)->exists()
            || TshirtImage::where('customer_id', $customer->id)->exists();

        if ($hasHistory) {

            // Soft delete para preservar histórico
            $customer->customer?->delete();
            $customer->delete();

            $message =
                'Cliente removido (soft delete - tem encomendas ou imagens associadas).';

        } else {

            // Eliminação definitiva se não existir histórico
            $customer->customer?->forceDelete();
            $customer->forceDelete();

            $message =
                'Cliente removido definitivamente.';
        }

        // Redireciona para a lista de clientes
        return redirect()
            ->route('admin.customers.index')
            ->with('success', $message);
    }
}