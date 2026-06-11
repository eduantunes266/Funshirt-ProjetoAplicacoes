<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->isEmployee() ? __('A minha conta') : __('O meu perfil') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Formulário de Informação de Perfil (Exclusivo para Não Funcionários) --}}
            @unless ($user->isEmployee())
                <div class="p-6 sm:p-8 bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            @endunless

            {{-- Formulário de Dados de Faturação (Exclusivo para Clientes) --}}
            @if ($user->isCustomer())
                <div class="p-6 sm:p-8 bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-billing-form')
                    </div>
                </div>
            @endif

            {{-- Formulário de Alteração de Palavra-passe --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
