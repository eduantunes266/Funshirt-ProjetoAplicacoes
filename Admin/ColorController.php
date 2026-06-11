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
    /**
     * Lista todas as cores disponíveis.
     */
    public function index(): View
    {
        // Obtém todas as cores ordenadas por nome
        $colors = Color::orderBy('name')->paginate(20);

        // Envia as cores para a view
        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Mostra o formulário de criação de cor.
     */
    public function create(): View
    {
        return view('admin.colors.create');
    }

    /**
     * Guarda uma nova cor.
     */
    public function store(StoreColorRequest $request): RedirectResponse
    {
        // Cria o registo da cor na base de dados
        Color::create(
            $request->safe()->only([
                'code',
                'name'
            ])
        );

        // Guarda a imagem base da t-shirt associada à cor
        $request->file('base_image')
            ->storeAs(
                'tshirt_base',
                $request->code . '.jpg',
                'public'
            );

        // Redireciona para a lista de cores
        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Cor criada com sucesso.');
    }

    /**
     * Mostra o formulário de edição de uma cor.
     */
    public function edit(Color $color): View
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Atualiza uma cor existente.
     */
    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        // Atualiza apenas o nome da cor
        $color->update(
            $request->safe()->only([
                'name'
            ])
        );

        // Se foi enviada uma nova imagem base
        if ($request->hasFile('base_image')) {

            // Substitui a imagem existente
            $request->file('base_image')
                ->storeAs(
                    'tshirt_base',
                    $color->code . '.jpg',
                    'public'
                );
        }

        // Redireciona para a lista de cores
        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Cor atualizada com sucesso.');
    }

    /**
     * Remove uma cor.
     */
    public function destroy(Color $color): RedirectResponse
    {
        // Remove a cor da base de dados
        $color->delete();

        // Redireciona para a lista de cores
        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Cor removida com sucesso.');
    }
}