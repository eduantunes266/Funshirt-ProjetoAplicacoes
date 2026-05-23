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
        $price = Price::first();

        $query = TshirtImage::whereNull('customer_id');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tshirts = $query->paginate(12)->withQueryString();

        return view('catalog.index', compact('tshirts', 'categories', 'price'));
    }
}