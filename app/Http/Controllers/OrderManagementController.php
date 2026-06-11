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
     * Funcionários só veem encomendas pendentes.
     * Administradores veem todas, com filtros por estado, cliente e data.
     */
    public function index(Request $request): View
    {
        // Obtém o utilizador autenticado
        $user = $request->user();

        // Cria a query das encomendas e carrega dados básicos do cliente
        $query = Order::query()->with('customer:id,name,email');

        // Se for funcionário, vê apenas encomendas pendentes
        if ($user->isEmployee()) {
            $query->where('status', 'pending');
        } else {
            // Se for administrador, pode filtrar por estado
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filtra por nome ou email do cliente
            if ($request->filled('customer')) {
                $search = $request->customer;

                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filtra encomendas a partir de uma data inicial
            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }

            // Filtra encomendas até uma data final
            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }
        }

        // Ordena as encomendas da mais recente para a mais antiga
        // e pagina os resultados, mantendo os filtros na URL
        $orders = $query->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // Envia as encomendas para a view
        return view('orders.index', compact('orders'));
    }

    /**
     * Detalhe de uma encomenda.
     * Funcionários só podem ver encomendas pendentes.
     * Administradores podem ver todas.
     */
    public function show(Request $request, Order $order): View
    {
        // Se for funcionário, só pode abrir encomendas pendentes
        if ($request->user()->isEmployee()) {
            abort_unless($order->status === 'pending', 403);
        }

        // Carrega os dados principais do cliente
        $order->load('customer:id,name,email');

        // Vai buscar os itens da encomenda com a respetiva imagem da t-shirt
        $items = OrderItem::with('tshirtImage')
            ->where('order_id', $order->id)
            ->get();

        // Envia a encomenda e os itens para a view
        return view('orders.show', compact('order', 'items'));
    }

    /**
     * Atualiza o estado de uma encomenda.
     * Funcionários só podem fechar encomendas.
     * Administradores podem fechar ou cancelar encomendas.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        // Obtém o utilizador autenticado
        $user = $request->user();

        // Define os estados permitidos conforme o tipo de utilizador
        $allowed = $user->isAdmin() ? 'closed,canceled' : 'closed';

        // Valida o novo estado e o motivo de cancelamento
        $validated = $request->validate([
            'status' => "required|in:{$allowed}",
            'reason_for_cancellation' => ['nullable', 'string', 'max:1000'],
        ]);

        // Impede processar uma encomenda que já foi fechada ou cancelada
        abort_unless(
            $order->status === 'pending',
            422,
            'A encomenda ja foi processada.'
        );

        // Atualiza o estado da encomenda
        $order->status = $validated['status'];

        // Procura o cliente associado à encomenda
        $customer = User::find($order->customer_id);

        // Se a encomenda for fechada, gera recibo e envia email
        if ($order->status === 'closed') {
            $this->generateReceiptAndNotify($order, $customer);
        } else {
            // Se a encomenda for cancelada, guarda o motivo
            $order->reason_for_cancellation =
                $validated['reason_for_cancellation'] ?? null;

            // Guarda as alterações
            $order->save();

            // Envia email ao cliente a informar o cancelamento
            if ($customer) {
                try {
                    Mail::to($customer->email)
                        ->send(new OrderCanceledMail($order));
                } catch (\Throwable $e) {
                    // Se o email falhar, apenas regista o erro
                    report($e);
                }
            }
        }

        // Volta para a página de detalhe da encomenda
        return redirect()->route('orders.show', $order);
    }

    /**
     * Gera o PDF do recibo, guarda-o em armazenamento privado
     * e envia email com o recibo em anexo ao cliente.
     */
    private function generateReceiptAndNotify(Order $order, ?User $customer): void
    {
        // Vai buscar os itens da encomenda com as imagens associadas
        $items = OrderItem::with('tshirtImage')
            ->where('order_id', $order->id)
            ->get();

        // Define o nome do cliente para o recibo
        $clientName = $customer?->name ?? 'Cliente';

        // Gera o PDF usando a view do recibo
        $pdf = Pdf::loadView(
            'pdf.receipt',
            compact('order', 'items', 'clientName')
        );

        // Cria um nome único para o ficheiro PDF
        $fileName = 'recibo_' . $order->id . '_' . time() . '.pdf';

        // Guarda o PDF no armazenamento local privado
        Storage::disk('local')->put(
            'pdf_receipts/' . $fileName,
            $pdf->output()
        );

        // Guarda o nome do recibo na encomenda
        $order->receipt_url = $fileName;

        // Guarda a encomenda com estado fechado e recibo
        $order->save();

        // Se existir cliente, envia email com o recibo
        if ($customer) {
            // Obtém o caminho físico do PDF guardado
            $pdfPath = Storage::disk('local')->path(
                'pdf_receipts/' . $fileName
            );

            try {
                Mail::to($customer->email)
                    ->send(new OrderClosedMail($order, $pdfPath));
            } catch (\Throwable $e) {
                // Se o envio falhar, regista o erro
                report($e);
            }
        }
    }
}