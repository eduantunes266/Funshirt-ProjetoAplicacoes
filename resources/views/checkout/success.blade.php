<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Encomenda Submetida') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <h1 class="text-2xl font-bold text-green-600 mb-3">Encomenda submetida com sucesso!</h1>
                <p class="text-gray-600 mb-6">
                    Obrigado pela sua compra. A encomenda foi registada no estado pendente e enviámos-lhe um email de confirmação.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('home') }}"
                       class="rounded-md bg-indigo-600 text-white font-semibold px-5 py-2 hover:bg-indigo-500">
                        Voltar ao catálogo
                    </a>
                    <a href="{{ route('my-orders.index') }}"
                       class="rounded-md bg-gray-200 text-gray-800 font-semibold px-5 py-2 hover:bg-gray-300">
                        As minhas encomendas
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
