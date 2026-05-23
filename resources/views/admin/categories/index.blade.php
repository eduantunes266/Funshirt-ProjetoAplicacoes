@extends('layouts.app')

@section('content')
<div style="padding: 30px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Gerir Categorias</h1>

        <a href="{{ route('admin.categories.create') }}"
           style="background:#28a745; color:white; padding:10px 16px; border-radius:5px; text-decoration:none;">
            Nova Categoria
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ccc;">ID</th>
                <th style="padding:10px; border:1px solid #ccc;">Nome</th>
                <th style="padding:10px; border:1px solid #ccc;">Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td style="padding:10px; border:1px solid #ccc;">
                        {{ $category->id }}
                    </td>

                    <td style="padding:10px; border:1px solid #ccc;">
                        {{ $category->name }}
                    </td>

                    <td style="padding:10px; border:1px solid #ccc;">
                        <div style="display:flex; gap:10px;">

                            <a href="{{ route('admin.categories.edit', $category) }}"
                               style="background:#007bff; color:white; padding:6px 12px; border-radius:4px; text-decoration:none;">
                                Editar
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Eliminar categoria?')"
                                        style="background:#dc3545; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer;">
                                    Eliminar
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $categories->links() }}
    </div>

</div>
@endsection