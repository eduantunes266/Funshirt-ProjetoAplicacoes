<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Lista os funcionarios e administradores.
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $staff = User::whereIn('user_type', ['F', 'A'])
            ->select('id', 'name', 'email', 'user_type', 'gender', 'blocked', 'photo_url')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Formulario de criacao de uma conta de staff.
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.staff.create');
    }

    /**
     * Guarda uma nova conta de funcionario/administrador.
     */
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'gender' => $request->gender,
            'blocked' => false,
        ]);

        // Contas de staff sao criadas pelo admin: nao precisam de verificar o email.
        $staff->markEmailAsVerified();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Contacriada com sucesso.');
    }

    /**
     * Formulario de edicao de uma conta de staff.
     */
    public function edit(User $staff): View
    {
        $this->authorize('update', $staff);
        $this->ensureIsStaff($staff);

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Atualiza uma conta de funcionario/administrador.
     */
    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        $this->authorize('update', $staff);
        $this->ensureIsStaff($staff);

        $staff->fill($request->safe()->only(['name', 'email', 'user_type', 'gender']));

        // Em branco mantem a palavra-passe atual.
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Contaatualizada com sucesso.');
    }

    /**
     * Remove (soft delete) uma conta de staff.
     */
    public function destroy(User $staff): RedirectResponse
    {
        $this->authorize('delete', $staff);
        $this->ensureIsStaff($staff);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Contaremovida com sucesso.');
    }

    /**
     * Garante que a conta alvo e mesmo staff (funcionario ou administrador).
     */
    private function ensureIsStaff(User $staff): void
    {
        abort_unless(in_array($staff->user_type, ['F', 'A'], true), 404);
    }
}
