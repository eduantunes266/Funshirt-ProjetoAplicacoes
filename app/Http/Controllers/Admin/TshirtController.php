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
    // Lista todas as t-shirts do catálogo (não personalizadas)
    public function index(): View
    {
        // Vai buscar as imagens do catálogo (customer_id nulo), com a categoria associada
        $tshirts = TshirtImage::with('category')
            ->whereNull('customer_id')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.tshirts.index', compact('tshirts'));
    }

    // Mostra o formulário para criar uma nova t-shirt para o catálogo
    public function create(): View
    {
        // Vai buscar as categorias disponíveis
        $categories = Category::orderBy('name')->get();
        return view('admin.tshirts.create', compact('categories'));
    }

    // Guarda a nova t-shirt na base de dados
    public function store(StoreTshirtRequest $request): RedirectResponse
    {
        // Obtém os dados validados, exceto a imagem
        $data = $request->safe()->except(['image']);
        // Garante que a t-shirt pertence ao catálogo (sem cliente associado)
        $data['customer_id'] = null;

        // Guarda a imagem
        $file = $request->file('image');
        $filename = uniqid('tshirt_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('tshirt_images', $filename, 'public');
        $data['image_url'] = $filename;

        // Cria o registo da t-shirt
        TshirtImage::create($data);

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt adicionada ao catalogo.');
    }

    // Mostra o formulário para editar uma t-shirt do catálogo
    public function edit(TshirtImage $tshirt): View
    {
        // Confirma que a t-shirt pertence ao catálogo
        abort_unless(is_null($tshirt->customer_id), 404);

        $categories = Category::orderBy('name')->get();
        return view('admin.tshirts.edit', compact('tshirt', 'categories'));
    }

    // Atualiza os dados da t-shirt
    public function update(UpdateTshirtRequest $request, TshirtImage $tshirt): RedirectResponse
    {
        // Confirma que a t-shirt pertence ao catálogo
        abort_unless(is_null($tshirt->customer_id), 404);

        $data = $request->safe()->except(['image']);

        // Se foi enviada uma nova imagem, apaga a antiga e guarda a nova
        if ($request->hasFile('image')) {
            if ($tshirt->image_url) {
                Storage::disk('public')->delete('tshirt_images/' . $tshirt->image_url);
            }

            $file = $request->file('image');
            $filename = uniqid('tshirt_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('tshirt_images', $filename, 'public');
            $data['image_url'] = $filename;
        }

        // Atualiza o registo
        $tshirt->update($data);

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt atualizada.');
    }

    // Remove a t-shirt do catálogo
    public function destroy(TshirtImage $tshirt): RedirectResponse
    {
        // Confirma que a t-shirt pertence ao catálogo
        abort_unless(is_null($tshirt->customer_id), 404);

        // Apaga o registo
        $tshirt->delete();

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt removida do catalogo.');
    }
}
