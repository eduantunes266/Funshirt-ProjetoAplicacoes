<x-guest-layout>
    {{-- Cabeçalho e Descrição --}}
    <h2 class="text-xl font-semibold text-gray-900 mb-2">Verifica o teu email</h2>
    <p class="text-sm text-gray-500 mb-4">
        {{ __('Obrigado pelo registo! Antes de continuar, confirma o teu email clicando no link que te enviámos. Se não recebeste, podemos reenviar.') }}
    </p>

    {{-- Mensagem de Sucesso no Reenvio --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ __('Foi enviado um novo link de verificação para o teu email.') }}
        </div>
    @endif

    {{-- Formulários de Ações --}}
    <div class="mt-4 flex items-center justify-between">
        {{-- Reenviar Email --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Reenviar email') }}
            </x-primary-button>
        </form>

        {{-- Terminar Sessão --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-indigo-600 transition">
                {{ __('Sair') }}
            </button>
        </form>
    </div>
</x-guest-layout>
