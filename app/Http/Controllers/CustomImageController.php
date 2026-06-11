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
    // Tamanhos válidos para as t-shirts
    private const VALID_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    // Diretoria onde as imagens privadas são guardadas
    private const DIR = 'tshirt_images_private';

    // Mostra a biblioteca de imagens personalizadas do cliente
    public function index(): View
    {
        // Vai buscar apenas as imagens do utilizador autenticado
        $images = TshirtImage::where('customer_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(12);

        // Envia imagens, cores, tamanhos e preço para a view
        return view('custom_images.index', [
            'images' => $images,
            'colors' => Color::orderBy('name')->get(),
            'sizes' => self::VALID_SIZES,
            'price' => Price::first(),
        ]);
    }

    // Mostra o formulário para adicionar uma imagem personalizada
    public function create(): View
    {
        return view('custom_images.create', [
            'price' => Price::first(),
        ]);
    }

    // Guarda uma nova imagem personalizada
    public function store(StoreCustomImageRequest $request): RedirectResponse
    {
        // Guarda o ficheiro da imagem e recebe o nome gerado
        $filename = $this->storeFile($request->file('image'));

        // Cria o registo da imagem na base de dados
        TshirtImage::create([
            'customer_id' => auth()->id(),
            'category_id' => null,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $filename,
        ]);

        // Redireciona para a biblioteca de imagens personalizadas
        return redirect()
            ->route('custom-images.index')
            ->with('success', 'Imagem adicionada a sua biblioteca.');
    }

    // Mostra o formulário para editar uma imagem personalizada
    public function edit(TshirtImage $customImage): View
    {
        // Verifica se o utilizador tem permissão para editar a imagem
        $this->authorize('update', $customImage);

        // Envia a imagem para a view de edição
        return view('custom_images.edit', ['image' => $customImage]);
    }

    // Atualiza uma imagem personalizada
    public function update(UpdateCustomImageRequest $request, TshirtImage $customImage): RedirectResponse
    {
        // Verifica se o utilizador tem permissão para atualizar a imagem
        $this->authorize('update', $customImage);

        // Atualiza o nome e a descrição da imagem
        $customImage->name = $request->name;
        $customImage->description = $request->description;

        // Se foi enviada uma nova imagem, substitui a antiga
        if ($request->hasFile('image')) {
            // Remove a imagem antiga do armazenamento
            Storage::disk('local')->delete(self::DIR.'/'.$customImage->image_url);

            // Guarda a nova imagem e atualiza o nome do ficheiro
            $customImage->image_url = $this->storeFile($request->file('image'));
        }

        // Guarda as alterações na base de dados
        $customImage->save();

        // Redireciona para a biblioteca
        return redirect()
            ->route('custom-images.index')
            ->with('success', 'Imagem atualizada.');
    }

    // Remove uma imagem personalizada
    public function destroy(TshirtImage $customImage): RedirectResponse
    {
        // Verifica se o utilizador tem permissão para eliminar
        $this->authorize('delete', $customImage);

        // Remove o registo da base de dados
        $customImage->delete();

        // Redireciona para a biblioteca
        return redirect()
            ->route('custom-images.index')
            ->with('success', 'Imagem removida.');
    }

    // Mostra o ficheiro da imagem privada
    public function file(TshirtImage $tshirtImage): StreamedResponse
    {
        // Verifica se o utilizador tem permissão para ver a imagem
        $this->authorize('view', $tshirtImage);

        // Caminho completo da imagem privada
        $path = self::DIR.'/'.$tshirtImage->image_url;

        // Se o ficheiro não existir, devolve erro 404
        abort_unless(Storage::disk('local')->exists($path), 404);

        // Devolve a imagem diretamente ao navegador
        return Storage::disk('local')->response($path);
    }

    // Guarda o ficheiro da imagem no armazenamento local
    private function storeFile(\Illuminate\Http\UploadedFile $file): string
    {
        // Cria um nome único para evitar ficheiros com nomes repetidos
        $filename = Str::uuid().'.'.$file->extension();

        // Guarda o ficheiro na diretoria privada
        $file->storeAs(self::DIR, $filename, 'local');

        // Devolve o nome do ficheiro guardado
        return $filename;
    }
}