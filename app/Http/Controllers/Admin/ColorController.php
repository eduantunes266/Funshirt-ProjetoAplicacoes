<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ColorController extends Controller
{
    // Lista todas as cores
    public function index(): View
    {
        // Vai buscar as cores ordenadas por nome, com paginação de 20
        $colors = Color::orderBy('name')->paginate(20);
        return view('admin.colors.index', compact('colors'));
    }

    // Mostra o formulário para criar uma nova cor
    public function create(): View
    {
        return view('admin.colors.create');
    }

    // Guarda uma nova cor na base de dados
    public function store(StoreColorRequest $request): RedirectResponse
    {
        // Cria a cor apenas com o código e o nome validados
        Color::create($request->safe()->only(['code', 'name']));

        // Guarda a imagem base associada ao código da cor
        $request->file('base_image')->storeAs('tshirt_base', $request->code . '.jpg', 'public');

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor criada com sucesso.');
    }

    // Mostra o formulário para editar uma cor existente
    public function edit(Color $color): View
    {
        return view('admin.colors.edit', compact('color'));
    }

    // Atualiza os dados de uma cor na base de dados
    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        // Atualiza apenas o nome da cor
        $color->update($request->safe()->only(['name']));

        // Se foi enviada uma nova imagem base, substitui a antiga
        if ($request->hasFile('base_image')) {
            $request->file('base_image')->storeAs('tshirt_base', $color->code . '.jpg', 'public');
        }

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor atualizada com sucesso.');
    }

    // Remove uma cor da base de dados
    public function destroy(Color $color): RedirectResponse
    {
        // Apaga a cor
        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor removida com sucesso.');
    }
}
