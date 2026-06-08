<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomImageRequest;
use App\Http\Requests\UpdateCustomImageRequest;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomImageController extends Controller
{
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    private const DIR = 'tshirt_images_private';

    public function index(): View
    {
        $images = TshirtImage::where('customer_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(12);

        return view('custom_images.index', [
            'images' => $images,
            'colors' => Color::orderBy('name')->get(),
            'sizes' => self::VALID_SIZES,
            'price' => Price::first(),
        ]);
    }

    public function create(): View
    {
        return view('custom_images.create', [
            'price' => Price::first(),
        ]);
    }

    public function store(StoreCustomImageRequest $request): RedirectResponse
    {
        $filename = $this->storeFile($request->file('image'));

        TshirtImage::create([
            'customer_id' => auth()->id(),
            'category_id' => null,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $filename,
        ]);

        return redirect()->route('custom-images.index')
            ->with('success', 'Imagem adicionada a sua biblioteca.');
    }

    public function edit(TshirtImage $customImage): View
    {
        $this->authorize('update', $customImage);

        return view('custom_images.edit', ['image' => $customImage]);
    }

    public function update(UpdateCustomImageRequest $request, TshirtImage $customImage): RedirectResponse
    {
        $this->authorize('update', $customImage);

        $customImage->name = $request->name;
        $customImage->description = $request->description;

        if ($request->hasFile('image')) {
            Storage::disk('local')->delete(self::DIR.'/'.$customImage->image_url);
            $customImage->image_url = $this->storeFile($request->file('image'));
        }

        $customImage->save();

        return redirect()->route('custom-images.index')
            ->with('success', 'Imagem atualizada.');
    }

    public function destroy(TshirtImage $customImage): RedirectResponse
    {
        $this->authorize('delete', $customImage);

        $customImage->delete();

        return redirect()->route('custom-images.index')
            ->with('success', 'Imagem removida.');
    }

    public function file(TshirtImage $tshirtImage): StreamedResponse
    {
        $this->authorize('view', $tshirtImage);

        $path = self::DIR.'/'.$tshirtImage->image_url;
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }

    private function storeFile(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = Str::uuid().'.'.$file->extension();
        $file->storeAs(self::DIR, $filename, 'local');

        return $filename;
    }
}
