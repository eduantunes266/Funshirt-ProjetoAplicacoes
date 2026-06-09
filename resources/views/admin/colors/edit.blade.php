<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cor') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6 sm:p-8">

                <form method="POST" action="{{ route('admin.colors.update', $color->code) }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-3">
                        <span class="inline-block h-10 w-10 rounded-lg border border-gray-300 shadow-sm"
                              style="background-color: #{{ $color->code }};"></span>
                        <div class="font-mono text-gray-700">#{{ $color->code }}</div>
                    </div>

                    <p class="text-xs text-gray-500">O código é a chave primária e está ligado às encomendas — não pode ser alterado.</p>

                    <div>
                        <x-input-label for="name" value="Nome" />
                        <x-text-input id="name" name="name" type="text"
                                      value="{{ old('name', $color->name) }}" required
                                      class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="base_image" value="Substituir imagem da t-shirt base (opcional)" />
                        <div class="mb-2">
                            <img src="{{ asset('storage/tshirt_base/' . $color->code . '.jpg') }}"
                                 alt="T-shirt base atual"
                                 class="h-24 w-24 object-contain border border-gray-200 rounded-lg bg-gradient-to-br from-slate-50 to-indigo-50/60"
                                 onerror="this.style.display='none'">
                        </div>
                        <input id="base_image" name="base_image" type="file" accept="image/jpeg,image/png"
                               class="mt-1 block w-full text-sm text-gray-700">
                        <p class="mt-1 text-xs text-gray-500">JPG/PNG até 4MB.</p>
                        <x-input-error :messages="$errors->get('base_image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.colors.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <x-primary-button>Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
