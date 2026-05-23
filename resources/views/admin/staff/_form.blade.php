@php
    $isEdit = isset($staff);
@endphp

<form method="post" action="{{ $isEdit ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('put')
    @endif

    <div>
        <x-input-label for="name" :value="__('Nome')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      :value="old('name', $isEdit ? $staff->name : '')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                      :value="old('email', $isEdit ? $staff->email : '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="user_type" :value="__('Tipo de conta')" />
        <select id="user_type" name="user_type" required
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="F" @selected(old('user_type', $isEdit ? $staff->user_type : 'F') === 'F')>Funcionário</option>
            <option value="A" @selected(old('user_type', $isEdit ? $staff->user_type : 'F') === 'A')>Administrador</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('user_type')" />
    </div>

    <div>
        <x-input-label for="gender" :value="__('Género')" />
        <select id="gender" name="gender" required
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="M" @selected(old('gender', $isEdit ? $staff->gender : 'M') === 'M')>Masculino</option>
            <option value="F" @selected(old('gender', $isEdit ? $staff->gender : 'M') === 'F')>Feminino</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
    </div>


    <div class="mt-6">
    <x-input-label for="password" value="Nova palavra-passe" />
    <p class="text-sm text-gray-500 mb-2">Deixe em branco para manter a palavra-passe atual.</p>
    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4 mb-6">
    <x-input-label for="password_confirmation" value="Confirmar palavra-passe" />
    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $isEdit ? __('Guardar alterações') : __('Criar conta') }}</x-primary-button>
        <a href="{{ route('admin.staff.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
