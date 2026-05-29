<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar imagem') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-5 flex justify-center bg-gray-50 rounded-md p-4">
                    <img src="{{ route('custom-images.file', $image) }}" alt="{{ $image->name }}" class="h-40 object-contain">
                </div>

                <form action="{{ route('custom-images.update', $image) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nome" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $image->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descrição (opcional)" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 text-sm">{{ old('description', $image->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image" value="Substituir imagem (opcional)" />
                        <input id="image" name="image" type="file" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-700">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Guardar</x-primary-button>
                        <a href="{{ route('custom-images.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
