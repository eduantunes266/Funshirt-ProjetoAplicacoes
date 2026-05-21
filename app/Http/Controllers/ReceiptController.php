<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    /**
     * Descarrega o recibo PDF de uma encomenda fechada.
     *
     * Acesso restrito pela OrderPolicy: apenas o cliente dono e administradores.
     */
    public function download(Order $order): StreamedResponse
    {
        $this->authorize('downloadReceipt', $order);

        // O recibo so existe depois de a encomenda ser fechada.
        abort_unless($order->receipt_url, 404);

        $path = 'pdf_receipts/'.$order->receipt_url;
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, 'recibo_'.$order->id.'.pdf');
    }
}
