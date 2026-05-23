<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova T-shirt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

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
