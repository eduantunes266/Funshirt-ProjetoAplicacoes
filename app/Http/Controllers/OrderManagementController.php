<?php

namespace App\Http\Controllers;

use App\Mail\OrderCanceledMail;
use App\Mail\OrderClosedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    /**
     * Lista de encomendas.
     * Funcionarios so veem encomendas pendentes (enunciado G4).
     * Administradores veem todas, com filtros por estado/data.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Order::query();

        if ($user->isEmployee()) {
            $query->where('status', 'pending');
        } else {
            // Administrador: filtros opcionais.
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }
        }

        $orders = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Detalhe de uma encomenda.
     * Funcionarios so podem ver pendentes; admin pode ver todas.
     */
    public function show(Request $request, Order $order): View
    {
        if ($request->user()->isEmployee()) {
            abort_unless($order->status === 'pending', 403);
        }

        $items = OrderItem::with('tshirtImage')
            ->where('order_id', $order->id)
            ->get();

        return view('orders.show', compact('order', 'items'));
    }

    /**
     * Transicao de estado.
     * Funcionarios: so podem marcar como "closed".
     * Administradores: podem marcar como "closed" ou "canceled".
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $user = $request->user();
        $allowed = $user->isAdmin() ? 'closed,canceled' : 'closed';

        $request->validate([
            'status' => "required|in:{$allowed}",
            'reason_for_cancellation' => 'nullable|required_if:status,canceled|string|max:500',
        ]);

        // Nao reprocessar uma encomenda ja fechada/anulada.
        abort_unless($order->status === 'pending', 422, 'A encomenda ja foi processada.');

        $order->status = $request->status;
        $customer = User::find($order->customer_id);

        if ($order->status === 'closed') {
            $this->generateReceiptAndNotify($order, $customer);
        } else {
            $order->reason_for_cancellation = $request->reason_for_cancellation;
            $order->save();

            if ($customer) {
                Mail::to($customer->email)->send(new OrderCanceledMail($order));
            }
        }

        return redirect()->route('orders.show', $order);
    }

    /**
     * Gera o PDF do recibo (private), guarda o nome em receipt_url
     * e envia o email com o anexo ao cliente.
     */
    private function generateReceiptAndNotify(Order $order, ?User $customer): void
    {
        $items = OrderItem::with('tshirtImage')->where('order_id', $order->id)->get();
        $clientName = $customer?->name ?? 'Cliente';

        $pdf = Pdf::loadView('pdf.receipt', compact('order', 'items', 'clientName'));

        $fileName = 'recibo_' . $order->id . '_' . time() . '.pdf';
        Storage::disk('local')->put('pdf_receipts/' . $fileName, $pdf->output());

        $order->receipt_url = $fileName;
        $order->save();

        if ($customer) {
            $pdfPath = Storage::disk('local')->path('pdf_receipts/' . $fileName);
            Mail::to($customer->email)->send(new OrderClosedMail($order, $pdfPath));
        }
    }
}
