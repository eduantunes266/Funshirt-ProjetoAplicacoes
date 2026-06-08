<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffController extends Controller
{

    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $staff = User::whereIn('user_type', ['F', 'A'])
            ->select('id', 'name', 'email', 'user_type', 'gender', 'blocked', 'photo_url')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.staff.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {

        $staff = User::create($request->safe()->only(['name', 'email', 'password', 'user_type', 'gender']) + [
            'blocked' => false,
        ]);

        $staff->markEmailAsVerified();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta criada com sucesso.');
    }

    public function edit(User $staff): View
    {
        $this->authorize('update', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        return view('admin.staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        $this->authorize('update', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        $editableFields = $staff->id === $request->user()->id
            ? ['name', 'email', 'gender']
            : ['name', 'email', 'user_type', 'gender'];

        $staff->fill($request->safe()->only($editableFields));

        if ($request->filled('password')) {
            $staff->password = $request->password;
        }

        $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta atualizada com sucesso.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        $this->authorize('delete', $staff);
        abort_unless($staff->isEmployee() || $staff->isAdmin(), 404);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Conta removida com sucesso.');
    }
}
