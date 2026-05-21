<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Utilizadores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-8">

                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Funcionários e Administradores</h3>
                    <a href="{{ route('admin.staff.create') }}"
                       class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        + Nova conta
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">Utilizador</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Tipo</th>
                                <th class="px-3 py-2">Estado</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($staff as $person)
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $person->photoLink() }}" alt="" class="h-9 w-9 rounded-full object-cover border border-gray-200">
                                            <span class="font-medium text-gray-900">{{ $person->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $person->email }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $person->user_type === 'A' ? 'Administrador' : 'Funcionário' }}</td>
                                    <td class="px-3 py-2">
                                        @if ($person->blocked)
                                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Bloqueado</span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Ativo</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.staff.edit', $person) }}" class="text-indigo-600 hover:underline">Editar</a>

                                            @if ($person->id !== auth()->id())
                                                <form method="post" action="{{ route('admin.accounts.toggle-block', $person) }}">
                                                    @csrf
                                                    @method('patch')
                                                    <button type="submit" class="text-amber-600 hover:underline">
                                                        {{ $person->blocked ? 'Desbloquear' : 'Bloquear' }}
                                                    </button>
                                                </form>
                                                <form method="post" action="{{ route('admin.staff.destroy', $person) }}"
                                                      onsubmit="return confirm('Remover a conta de {{ $person->name }}?')">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="text-red-600 hover:underline">Remover</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">(a sua conta)</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">Sem contas a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $staff->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
