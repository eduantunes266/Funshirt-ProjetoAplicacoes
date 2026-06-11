<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    /**
     * Permite descarregar o recibo PDF de uma encomenda.
     */
    public function download(Order $order): StreamedResponse
    {
        // Verifica se o utilizador tem permissão
        // para descarregar o recibo desta encomenda
        $this->authorize('downloadReceipt', $order);

        // Verifica se a encomenda possui recibo associado
        abort_unless($order->receipt_url, 404);

        // Caminho do ficheiro PDF no armazenamento privado
        $path = 'pdf_receipts/' . $order->receipt_url;

        // Verifica se o ficheiro existe
        abort_unless(
            Storage::disk('local')->exists($path),
            404
        );

        // Faz o download do PDF com um nome amigável
        return Storage::disk('local')->download(
            $path,
            'recibo_' . $order->id . '.pdf'
        );
    }
}