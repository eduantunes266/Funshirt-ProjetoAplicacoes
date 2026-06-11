<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova conta de funcionário / administrador') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">
                {{-- Formulário reaproveitado para criar e editar funcionários --}}
                @include('admin.staff._form')
            </div>
        </div>
    </div>
</x-app-layout>
