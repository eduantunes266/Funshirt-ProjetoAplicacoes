<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoria') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">

                {{-- Formulário de edição de categoria --}}
                <form method="POST" action="{{ route('admin.categories.update', $category) }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Inclui os campos partilhados do formulário passando os dados da categoria --}}
                    @include('admin.categories._form', ['category' => $category])

                    {{-- Botões de ação --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <x-primary-button>Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
