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
    public function index(): View
    {
        $tshirts = TshirtImage::with('category')
            ->whereNull('customer_id')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.tshirts.index', compact('tshirts'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.tshirts.create', compact('categories'));
    }

    public function store(StoreTshirtRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['customer_id'] = null;

        $file = $request->file('image');
        $filename = uniqid('tshirt_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('tshirt_images', $filename, 'public');
        $data['image_url'] = $filename;

        TshirtImage::create($data);

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt adicionada ao catalogo.');
    }

    public function edit(TshirtImage $tshirt): View
    {
        abort_unless(is_null($tshirt->customer_id), 404);

        $categories = Category::orderBy('name')->get();
        return view('admin.tshirts.edit', compact('tshirt', 'categories'));
    }

    public function update(UpdateTshirtRequest $request, TshirtImage $tshirt): RedirectResponse
    {
        abort_unless(is_null($tshirt->customer_id), 404);

        $data = $request->safe()->except(['image']);

        if ($request->hasFile('image')) {
            if ($tshirt->image_url) {
                Storage::disk('public')->delete('tshirt_images/' . $tshirt->image_url);
            }

            $file = $request->file('image');
            $filename = uniqid('tshirt_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('tshirt_images', $filename, 'public');
            $data['image_url'] = $filename;
        }

        $tshirt->update($data);

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt atualizada.');
    }

    public function destroy(TshirtImage $tshirt): RedirectResponse
    {
        abort_unless(is_null($tshirt->customer_id), 404);

        $tshirt->delete();

        return redirect()->route('admin.tshirts.index')
            ->with('success', 'T-shirt removida do catalogo.');
    }
}
