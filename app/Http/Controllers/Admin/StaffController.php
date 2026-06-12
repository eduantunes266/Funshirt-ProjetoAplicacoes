<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    // Lista todos os funcionários e administradores
    public function index(Request $request): View
    {
        // Verifica as permissões para ver utilizadores
        $this->authorize('viewAny', User::class);

        // Obtém os parâmetros de pesquisa e estado do pedido
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'ativos');

        // Vai buscar os utilizadores que sejam do tipo Funcionário (F) ou Administrador (A)
        $staff = User::whereIn('user_type', ['F', 'A'])
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
            ->select('id', 'name', 'email', 'user_type', 'gender', 'blocked', 'photo_url', 'deleted_at')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.staff.index', compact('staff', 'search', 'status'));
    }

    // Mostra o formulário para criar um novo funcionário/administrador
    public function create(): View
    {
        // Verifica as permissões para criar utilizadores
        $this->authorize('create', User::class);

        return view('admin.staff.create');
    }

    // Guarda o novo funcionário/administrador na base de dados
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        // Cria o utilizador com os dados validados e define como não bloqueado por defeito
        $staff = User::create($request->safe()->only(['name', 'email', 'password', 'user_type', 'gender']) + [
            'blocked' => false,
        ]);

        // Marca o email como verificado automaticamente
        $staff->markEmailAsVerified();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta criada com sucesso.');
    }

    // Mostra o formulário para editar um funcionário/administrador
    public function edit(User $staff): View
    {
        // Verifica as permissões de edição
        $this->authorize('update', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        return view('admin.staff.edit', compact('staff'));
    }

    // Atualiza os dados de um funcionário/administrador
    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        // Verifica as permissões de edição
        $this->authorize('update', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        // Se o utilizador estiver a editar o próprio perfil, não pode mudar o seu tipo
        $editableFields = $staff->id === $request->user()->id
            ? ['name', 'email', 'gender']
            : ['name', 'email', 'user_type', 'gender'];

        // Preenche os dados com base nos campos permitidos
        $staff->fill($request->safe()->only($editableFields));

        // Se a password foi preenchida, atualiza-a
        if ($request->filled('password')) {
            $staff->password = $request->password;
        }

        // Guarda as alterações
        $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta atualizada com sucesso.');
    }

    // Remove um funcionário/administrador
    public function destroy(User $staff): RedirectResponse
    {
        // Verifica as permissões de eliminação
        $this->authorize('delete', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        // Apaga a conta
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta removida com sucesso.');
    }
}
