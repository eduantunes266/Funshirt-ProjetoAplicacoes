{{-- Variáveis auxiliares para determinar se estamos a criar ou a editar --}}
@php
    $isEdit = isset($staff);
    $editingSelf = $isEdit && $staff->id === auth()->id();
@endphp

{{-- Formulário dinâmico que envia para a rota de update se estivermos a editar, ou store caso contrário --}}
<form method="post" action="{{ $isEdit ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('put')
    @endif

    {{-- Campo Nome --}}
    <div>
        <x-input-label for="name" :value="__('Nome')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      :value="old('name', $isEdit ? $staff->name : '')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    {{-- Campo Email --}}
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                      :value="old('email', $isEdit ? $staff->email : '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    {{-- Campo Tipo de Conta (Funcionário ou Administrador) --}}
    <div>
        <x-input-label for="user_type" :value="__('Tipo de conta')" />
        <select id="user_type" name="user_type" required @disabled($editingSelf)
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
            <option value="F" @selected(old('user_type', $isEdit ? $staff->user_type : 'F') === 'F')>Funcionário</option>
            <option value="A" @selected(old('user_type', $isEdit ? $staff->user_type : 'F') === 'A')>Administrador</option>
        </select>
        
        {{-- Proteção para evitar que o utilizador remova os seus próprios privilégios de administrador --}}
        @if ($editingSelf)
            <input type="hidden" name="user_type" value="{{ $staff->user_type }}">
            <p class="mt-1 text-xs text-gray-500">Não pode alterar o tipo da sua própria conta.</p>
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('user_type')" />
    </div>

    {{-- Campo Género --}}
    <div>
        <x-input-label for="gender" :value="__('Género')" />
        <select id="gender" name="gender" required
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
            <option value="M" @selected(old('gender', $isEdit ? $staff->gender : 'M') === 'M')>Masculino</option>
            <option value="F" @selected(old('gender', $isEdit ? $staff->gender : 'M') === 'F')>Feminino</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
    </div>

    {{-- Campo Palavra-passe --}}
    <div>
        <x-input-label for="password" :value="$isEdit ? __('Nova palavra-passe') : __('Palavra-passe')" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                      :required="! $isEdit" autocomplete="new-password" />
        @if ($isEdit)
            <p class="mt-1 text-xs text-gray-500">Deixe em branco para manter a palavra-passe atual.</p>
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
    </div>

    {{-- Campo Confirmar Palavra-passe --}}
    <div>
        <x-input-label for="password_confirmation" :value="__('Confirmar palavra-passe')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
                      :required="! $isEdit" autocomplete="new-password" />
    </div>

    {{-- Botões de Ação --}}
    <div class="flex items-center gap-4">
        <x-primary-button>{{ $isEdit ? __('Guardar alterações') : __('Criar conta') }}</x-primary-button>
        <a href="{{ route('admin.staff.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
