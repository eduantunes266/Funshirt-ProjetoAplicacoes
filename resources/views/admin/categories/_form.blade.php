@props(['category' => null])

<div>
    <x-input-label for="name" value="Nome" />
    <x-text-input id="name" name="name" type="text"
                  value="{{ old('name', $category?->name) }}" required
                  class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="image" :value="$category ? 'Substituir imagem (opcional)' : 'Imagem (opcional)'" />
    @if ($category && $category->image_url)
        <div class="mb-2">
            <img src="{{ asset('storage/categories/' . $category->image_url) }}"
                 alt="Imagem atual"
                 class="h-24 w-24 object-contain border border-gray-200 rounded-lg bg-gradient-to-br from-slate-50 to-indigo-50/60">
        </div>
    @endif
    <input id="image" name="image" type="file" accept="image/*"
           class="mt-1 block w-full text-sm text-gray-700">
    <p class="mt-1 text-xs text-gray-500">JPG/PNG/GIF/WEBP, até 4MB.</p>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>
