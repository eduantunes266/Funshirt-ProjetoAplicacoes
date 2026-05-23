@extends('layouts.app')

@section('content')
<div style="max-width:600px; margin:40px auto; background:white; padding:30px; border-radius:8px;">

    <h1 style="margin-bottom:20px;">Editar Categoria</h1>

    @if($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:20px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold;">
                Nome da Categoria
            </label>

            <input type="text"
                   name="name"
                   value="{{ old('name', $category->name) }}"
                   required
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="display:flex; gap:10px;">

            <button type="submit"
                    style="background:#007bff; color:white; border:none; padding:10px 18px; border-radius:5px; cursor:pointer;">
                Atualizar
            </button>

            <a href="{{ route('admin.categories.index') }}"
               style="background:#6c757d; color:white; padding:10px 18px; border-radius:5px; text-decoration:none;">
                Cancelar
            </a>

        </div>
    </form>

</div>
@endsection