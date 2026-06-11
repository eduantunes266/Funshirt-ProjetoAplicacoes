<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Cor') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">

                {{-- Formulário de criação de cor --}}
                <form method="POST" action="{{ route('admin.colors.store') }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Campo Código da Cor --}}
                    <div>
                        <x-input-label for="code" value="Código CSS" />
                        <x-text-input id="code" name="code" type="text" maxlength="50"
                                      value="{{ old('code') }}" required
                                      class="mt-1 block w-full font-mono" placeholder="ac283b" />
                        <p class="mt-1 text-xs text-gray-500">Código CSS válido — hex (ex: <code>ac283b</code>) ou nome (ex: <code>black</code>).</p>
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Campo Nome da Cor --}}
                    <div>
                        <x-input-label for="name" value="Nome" />
                        <x-text-input id="name" name="name" type="text"
                                      value="{{ old('name') }}" required
                                      class="mt-1 block w-full" placeholder="Vermelho" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Upload da Imagem Base --}}
                    <div>
                        <x-input-label for="base_image" value="Imagem da t-shirt base" />
                        <input id="base_image" name="base_image" type="file" accept="image/jpeg,image/png" required
                               class="mt-1 block w-full text-sm text-gray-700">
                        <p class="mt-1 text-xs text-gray-500">JPG/PNG até 4MB. Será guardada como <code>{código}.jpg</code>.</p>
                        <x-input-error :messages="$errors->get('base_image')" class="mt-2" />
                    </div>

                    {{-- Botões de ação --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.colors.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <x-primary-button>Criar cor</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
