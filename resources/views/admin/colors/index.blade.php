<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão do Catálogo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-8">

                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Cores</h3>
                    <a href="{{ route('admin.colors.create') }}"
                       class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        + Nova cor
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">Amostra</th>
                                <th class="px-3 py-2">Código</th>
                                <th class="px-3 py-2">Nome</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($colors as $color)
                                <tr>
                                    <td class="px-3 py-2">
                                        <span class="inline-block h-8 w-8 rounded border border-gray-300"
                                              style="background-color: #{{ $color->code }};"></span>
                                    </td>
                                    <td class="px-3 py-2 font-mono text-gray-700">#{{ $color->code }}</td>
                                    <td class="px-3 py-2 text-gray-900">{{ $color->name }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.colors.edit', $color->code) }}" class="text-indigo-600 hover:underline">Editar</a>

                                            <form method="post" action="{{ route('admin.colors.destroy', $color->code) }}"
                                                  onsubmit="return confirm('Remover a cor {{ $color->name }}?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-red-600 hover:underline">Remover</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-6 text-center text-gray-500">Sem cores a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $colors->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
