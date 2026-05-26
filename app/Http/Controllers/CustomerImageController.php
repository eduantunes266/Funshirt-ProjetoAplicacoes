<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerImageController extends Controller
{
    public function index(Request $request)
    {
        $images = TshirtImage::where('customer_id', $request->user()->id)
            ->latest()
            ->get();

        return view('customer.images.index', compact('images'));
    }

    public function showPrivateImage(Request $request, TshirtImage $tshirtImage)
    {
        abort_unless($tshirtImage->customer_id === $request->user()->id || $request->user()->isAdmin(), 403);

        $path = $tshirtImage->image_url;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }

    public function destroy(Request $request, TshirtImage $tshirtImage)
    {
        abort_unless($tshirtImage->customer_id === $request->user()->id, 403);

        if ($tshirtImage->image_url) {
            Storage::disk('local')->delete($tshirtImage->image_url);
        }

        $tshirtImage->delete();

        return redirect()
            ->route('customer.images.index')
            ->with('success', 'Imagem apagada com sucesso.');
    }

    public function edit(Request $request, TshirtImage $tshirtImage)
    {
        abort_unless($tshirtImage->customer_id === $request->user()->id, 403);

        return view('customer.images.edit', compact('tshirtImage'));
    }

    public function update(Request $request, TshirtImage $tshirtImage)
    {
        abort_unless($tshirtImage->customer_id === $request->user()->id, 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($tshirtImage->image_url) {
                Storage::disk('local')->delete($tshirtImage->image_url);
            }

            $tshirtImage->image_url = $request->file('image')->store('tshirt_images_private', 'local');
        }

        $tshirtImage->name = $request->name;
        $tshirtImage->description = $request->description;
        $tshirtImage->save();

        return redirect()->route('customer.images.index')
            ->with('success', 'Imagem atualizada com sucesso.');
    }
}