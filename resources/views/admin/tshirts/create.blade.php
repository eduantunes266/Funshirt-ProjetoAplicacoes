<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova T-shirt') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">

                <form method="POST" action="{{ route('admin.tshirts.store') }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    @include('admin.tshirts._form', ['categories' => $categories])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.tshirts.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <x-primary-button>Criar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
