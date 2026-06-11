<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    // Lista todas as categorias
    public function index(): View
    {
        // Vai buscar as categorias ordenadas por nome, com paginação de 15
        $categories = Category::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    // Mostra o formulário para criar uma nova categoria
    public function create(): View
    {
        return view('admin.categories.create');
    }

    // Guarda uma nova categoria na base de dados
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        // Obtém os dados validados, exceto a imagem
        $data = $request->safe()->except(['image']);

        // Se foi enviada uma imagem, guarda-a e adiciona o url aos dados
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        // Cria a categoria com os dados
        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    // Mostra o formulário para editar uma categoria existente
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Atualiza os dados de uma categoria na base de dados
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        // Obtém os dados validados, exceto a imagem
        $data = $request->safe()->except(['image']);

        // Se foi enviada uma nova imagem
        if ($request->hasFile('image')) {
            // Apaga a imagem antiga se existir
            if ($category->image_url) {
                Storage::disk('public')->delete('categories/' . $category->image_url);
            }
            // Guarda a nova imagem e adiciona o url aos dados
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        // Atualiza a categoria
        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    // Remove uma categoria da base de dados
    public function destroy(Category $category): RedirectResponse
    {
        // Apaga a categoria
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria removida com sucesso.');
    }

    // Função auxiliar para guardar as imagens das categorias
    private function storeImage($file): string
    {
        // Gera um nome único para a imagem
        $filename = uniqid('cat_') . '.' . $file->getClientOriginalExtension();
        // Guarda no disco público
        $file->storeAs('categories', $filename, 'public');
        return $filename;
    }
}
