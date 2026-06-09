<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar T-shirt') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">

                <form method="POST" action="{{ route('admin.tshirts.update', $tshirt) }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @include('admin.tshirts._form', ['tshirt' => $tshirt, 'categories' => $categories])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.tshirts.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <x-primary-button>Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
