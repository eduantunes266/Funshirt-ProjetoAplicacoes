<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerImageController extends Controller
{
    // Mostra todas as imagens privadas do cliente autenticado
    public function index(Request $request)
    {
        // Obtém apenas as imagens pertencentes ao utilizador autenticado
        $images = TshirtImage::where('customer_id', $request->user()->id)
            ->latest()
            ->get();

        // Envia as imagens para a view
        return view('customer.images.index', compact('images'));
    }

    // Mostra uma imagem privada
    public function showPrivateImage(Request $request, TshirtImage $tshirtImage)
    {
        // Apenas o proprietário da imagem ou um administrador podem visualizar
        abort_unless(
            $tshirtImage->customer_id === $request->user()->id ||
            $request->user()->isAdmin(),
            403
        );

        // Caminho da imagem guardado na base de dados
        $path = $tshirtImage->image_url;

        // Verifica se o ficheiro existe
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        // Devolve a imagem ao navegador
        return Storage::disk('local')->response($path);
    }

    // Remove uma imagem privada
    public function destroy(Request $request, TshirtImage $tshirtImage)
    {
        // Apenas o proprietário pode apagar a imagem
        abort_unless(
            $tshirtImage->customer_id === $request->user()->id,
            403
        );

        // Remove o ficheiro físico do armazenamento
        if ($tshirtImage->image_url) {
            Storage::disk('local')->delete($tshirtImage->image_url);
        }

        // Remove o registo da base de dados
        $tshirtImage->delete();

        // Redireciona para a lista de imagens
        return redirect()
            ->route('customer.images.index')
            ->with('success', 'Imagem apagada com sucesso.');
    }

    // Mostra o formulário de edição de uma imagem
    public function edit(Request $request, TshirtImage $tshirtImage)
    {
        // Apenas o proprietário pode editar
        abort_unless(
            $tshirtImage->customer_id === $request->user()->id,
            403
        );

        // Envia a imagem para a view de edição
        return view('customer.images.edit', compact('tshirtImage'));
    }

    // Atualiza os dados da imagem
    public function update(Request $request, TshirtImage $tshirtImage)
    {
        // Apenas o proprietário pode atualizar
        abort_unless(
            $tshirtImage->customer_id === $request->user()->id,
            403
        );

        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Se foi carregada uma nova imagem
        if ($request->hasFile('image')) {

            // Remove a imagem antiga do armazenamento
            if ($tshirtImage->image_url) {
                Storage::disk('local')->delete($tshirtImage->image_url);
            }

            // Guarda a nova imagem e atualiza o caminho
            $tshirtImage->image_url = $request->file('image')
                ->store('tshirt_images_private', 'local');
        }

        // Atualiza nome e descrição
        $tshirtImage->name = $request->name;
        $tshirtImage->description = $request->description;

        // Guarda as alterações na base de dados
        $tshirtImage->save();

        // Redireciona para a lista de imagens
        return redirect()
            ->route('customer.images.index')
            ->with('success', 'Imagem atualizada com sucesso.');
    }
}