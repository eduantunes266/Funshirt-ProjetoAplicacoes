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
    public function index(): View
    {
        $colors = Color::orderBy('name')->paginate(20);
        return view('admin.colors.index', compact('colors'));
    }

    public function create(): View
    {
        return view('admin.colors.create');
    }

    public function store(StoreColorRequest $request): RedirectResponse
    {
        Color::create($request->safe()->only(['code', 'name']));

        // T-shirt base: filename = code (.jpg). Override extension to .jpg para consistencia.
        $request->file('base_image')->storeAs('tshirt_base', $request->code . '.jpg', 'public');

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor criada com sucesso.');
    }

    public function edit(Color $color): View
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        $color->update($request->safe()->only(['name']));

        if ($request->hasFile('base_image')) {
            $request->file('base_image')->storeAs('tshirt_base', $color->code . '.jpg', 'public');
        }

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor atualizada com sucesso.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        // Soft delete da cor; mantemos o ficheiro tshirt_base em disco
        // para encomendas historicas que ainda referenciam esta cor.
        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cor removida com sucesso.');
    }
}
