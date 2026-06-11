{{-- Variáveis e propriedades passadas para o componente --}}
@props(['tshirt' => null, 'categories'])

{{-- Campo Nome --}}
<div>
    <x-input-label for="name" value="Nome" />
    <x-text-input id="name" name="name" type="text"
                  value="{{ old('name', $tshirt?->name) }}" required
                  class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

{{-- Campo Descrição --}}
<div>
    <x-input-label for="description" value="Descrição" />
    <textarea id="description" name="description" rows="3"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">{{ old('description', $tshirt?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

{{-- Campo Categoria --}}
<div>
    <x-input-label for="category_id" value="Categoria (opcional)" />
    <select id="category_id" name="category_id"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
        <option value="">— Sem categoria —</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $tshirt?->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
</div>

{{-- Campo Imagem --}}
<div>
    <x-input-label for="image" :value="$tshirt ? 'Substituir imagem (opcional)' : 'Imagem'" />
    
    {{-- Pré-visualização da imagem atual --}}
    @if ($tshirt && $tshirt->image_url)
        <div class="mb-2">
            <img src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                 alt="Imagem atual"
                 class="h-32 w-32 object-contain border border-gray-200 rounded-lg bg-gradient-to-br from-slate-50 to-indigo-50/60">
        </div>
    @endif
    
    {{-- Upload de nova imagem --}}
    <input id="image" name="image" type="file" accept="image/*"
           class="mt-1 block w-full text-sm text-gray-700">
    <p class="mt-1 text-xs text-gray-500">JPG/PNG/GIF/WEBP, até 4MB.</p>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>
