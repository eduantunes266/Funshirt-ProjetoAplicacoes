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
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    public function index(Request $request): View
    {
        $query = TshirtImage::query()->whereNull('customer_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tshirts = $query->paginate(12)->withQueryString();

        return view('catalog.index', [
            'tshirts' => $tshirts,
            'categories' => Category::orderBy('name')->get(),
            'price' => Price::first(),
            'colors' => Color::orderBy('name')->get(),
            'sizes' => self::VALID_SIZES,
        ]);
    }
}
