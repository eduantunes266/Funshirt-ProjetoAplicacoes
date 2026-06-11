<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Encomenda Submetida') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-10">
        <div class="max-w-xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-8 text-center">
                {{-- Ícone de Sucesso --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-4">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                
                {{-- Mensagem de Sucesso --}}
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Encomenda submetida com sucesso!</h1>
                <p class="text-gray-600 mb-6">
                    Obrigado pela sua compra. A encomenda foi registada no estado pendente e enviámos-lhe um email de confirmação.
                </p>
                
                {{-- Botões de Navegação --}}
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home') }}"
                       class="rounded-lg bg-indigo-600 text-white font-semibold px-5 py-2 shadow-sm hover:bg-indigo-700 transition">
                        Voltar ao catálogo
                    </a>
                    <a href="{{ route('my-orders.index') }}"
                       class="rounded-lg bg-white border border-gray-300 text-gray-800 font-semibold px-5 py-2 shadow-sm hover:bg-gray-50 transition">
                        As minhas encomendas
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
