<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TshirtImage;
use App\Models\Category;
use App\Models\Price;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $price = Price::first(); // Vai buscar a linha única de preços
        
        $query = TshirtImage::whereNull('customer_id');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tshirts = $query->get();

        return view('catalog.index', compact('tshirts', 'categories', 'price'));
    }
}