<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTshirtRequest;
use App\Http\Requests\UpdateTshirtRequest;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TshirtController extends Controller
{
    /**
     * Lista todas as t-shirts do catálogo.
     */
    public function index(): View
    {
        // Obtém apenas as t-shirts do catálogo
        // (não inclui imagens personalizadas dos clientes)
        $tshirts = TshirtImage::with('category')
            ->whereNull('customer_id')
            ->orderBy('name')
            ->paginate(15);

        // Envia as t-shirts para a view
        return view('admin.tshirts.index', compact('tshirts'));
    }

    /**
     * Mostra o formulário para criar uma nova t-shirt.
     */
    public function create(): View
    {
        // Obtém todas as categorias disponíveis
        $categories = Category::orderBy('name')->get();

        // Envia as categorias para a view
        return view('admin.tshirts.create', compact('categories'));
    }

    /**
     * Guarda uma nova t-shirt no catálogo.
     */
    public function store(StoreTshirtRequest $request): RedirectResponse
    {
        // Obtém os dados validados exceto a imagem
        $data = $request->safe()->except(['image']);

        // Garante que pertence ao catálogo
        $data['customer_id'] = null;

        // Obtém o ficheiro da imagem
        $file = $request->file('image');

        // Gera um nome único para o ficheiro
        $filename = uniqid('tshirt_') . '.' . $file->getClientOriginalExtension();

        // Guarda a imagem no armazenamento público
        $file->storeAs('tshirt_images', $filename, 'public');

        // Guarda o nome da imagem
        $data['image_url'] = $filename;

        // Cria a t-shirt na base de dados
        TshirtImage::create($data);

        // Redireciona para a lista do catálogo
        return redirect()
            ->route('admin.tshirts.index')
            ->with('success', 'T-shirt adicionada ao catalogo.');
    }

    /**
     * Mostra o formulário de edição de uma t-shirt.
     */
    public function edit(TshirtImage $tshirt): View
    {
        // Apenas t-shirts do catálogo podem ser editadas
        abort_unless(is_null($tshirt->customer_id), 404);

        // Obtém todas as categorias
        $categories = Category::orderBy('name')->get();

        // Envia os dados para a view
        return view(
            'admin.tshirts.edit',
            compact('tshirt', 'categories')
        );
    }

    /**
     * Atualiza uma t-shirt existente.
     */
    public function update(
        UpdateTshirtRequest $request,
        TshirtImage $tshirt
    ): RedirectResponse
    {
        // Apenas t-shirts do catálogo podem ser atualizadas
        abort_unless(is_null($tshirt->customer_id), 404);

        // Obtém os dados validados exceto a imagem
        $data = $request->safe()->except(['image']);

        // Se foi enviada uma nova imagem
        if ($request->hasFile('image')) {

            // Remove a imagem antiga
            if ($tshirt->image_url) {
                Storage::disk('public')
                    ->delete('tshirt_images/' . $tshirt->image_url);
            }

            // Guarda a nova imagem
            $file = $request->file('image');

            $filename =
                uniqid('tshirt_') . '.' .
                $file->getClientOriginalExtension();

            $file->storeAs(
                'tshirt_images',
                $filename,
                'public'
            );

            // Atualiza o nome da imagem
            $data['image_url'] = $filename;
        }

        // Atualiza a t-shirt
        $tshirt->update($data);

        // Redireciona para a lista
        return redirect()
            ->route('admin.tshirts.index')
            ->with('success', 'T-shirt atualizada.');
    }

    /**
     * Remove uma t-shirt do catálogo.
     */
    public function destroy(TshirtImage $tshirt): RedirectResponse
    {
        // Apenas t-shirts do catálogo podem ser removidas
        abort_unless(is_null($tshirt->customer_id), 404);

        // Remove a t-shirt da base de dados
        $tshirt->delete();

        // Redireciona para a lista
        return redirect()
            ->route('admin.tshirts.index')
            ->with('success', 'T-shirt removida do catalogo.');
    }
}