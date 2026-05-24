<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            As Minhas Imagens
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if($images->isEmpty())
                    <p class="text-gray-500">
                        Ainda não tens imagens personalizadas.
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        @foreach($images as $image)
                            <div class="border rounded-lg p-4 bg-gray-50">

                                <x-tshirt-preview
                                    :image="$image->image_url"
                                    size="md" />

                                <div class="mt-4">
                                    <p class="font-semibold text-gray-900">
                                        {{ $image->name }}
                                    </p>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $image->description }}
                                    </p>
                                </div>
                                
                                <a href="{{ route('customer.images.edit', $image) }}"
                                class="block w-full text-center rounded-md bg-indigo-600 text-white py-2 text-sm font-semibold hover:bg-indigo-500 mt-4">
                                    Editar
                                </a>

                                <form action="{{ route('customer.images.destroy', $image) }}"
                                      method="POST"
                                      class="mt-4"
                                      onsubmit="return confirm('Apagar esta imagem?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="w-full rounded-md bg-red-600 text-white py-2 text-sm font-semibold hover:bg-red-500">
                                        Apagar
                                    </button>
                                </form>

                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>