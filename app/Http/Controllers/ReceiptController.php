<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    // Faz o download do recibo (PDF) de uma encomenda
    public function download(Order $order): StreamedResponse
    {
        // Verifica se o utilizador autenticado tem permissão para fazer o download deste recibo
        $this->authorize('downloadReceipt', $order);

        // Se a encomenda não tiver URL de recibo, devolve erro 404 (Não Encontrado)
        abort_unless($order->receipt_url, 404);

        // Define o caminho do ficheiro do recibo
        $path = 'pdf_receipts/'.$order->receipt_url;
        
        // Se o ficheiro não existir no disco local, devolve erro 404
        abort_unless(Storage::disk('local')->exists($path), 404);

        // Faz o download do ficheiro com um nome descritivo (ex: recibo_1.pdf)
        return Storage::disk('local')->download($path, 'recibo_'.$order->id.'.pdf');
    }
}
