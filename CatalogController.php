<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    // Tamanhos válidos disponíveis para as t-shirts
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    // Mostra a página do catálogo
    public function index(Request $request): View
    {
        // Cria a query para ir buscar apenas t-shirts do catálogo
        // Ou seja, ignora t-shirts personalizadas dos clientes
        $query = TshirtImage::query()->whereNull('customer_id');

        // Se existir pesquisa, filtra por nome ou descrição
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Se existir uma categoria selecionada, filtra por categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Vai buscar as t-shirts, 12 por página,
        // mantendo os filtros na paginação
        $tshirts = $query->paginate(12)->withQueryString();

        // Envia os dados necessários para a view do catálogo
        return view('catalog.index', [
            'tshirts' => $tshirts,

            // Lista de categorias ordenadas por nome
            'categories' => Category::orderBy('name')->get(),

            // Regra de preços atual
            'price' => Price::first(),

            // Lista de cores disponíveis
            'colors' => Color::orderBy('name')->get(),

            // Lista de tamanhos válidos
            'sizes' => self::VALID_SIZES,
        ]);
    }
}