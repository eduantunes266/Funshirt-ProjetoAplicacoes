<?php

namespace App\Http\Controllers;

use App\Mail\OrderCanceledMail;
use App\Mail\OrderClosedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Order::query();

        if ($user->isEmployee()) {
            $query->where('status', 'pending');
        } else {
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

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $user = $request->user();
        $allowed = $user->isAdmin() ? 'closed,canceled' : 'closed';

        $rules = [
            'status' => "required|in:{$allowed}",
        ];

        if ($request->status === 'canceled') {
            $rules['reason_for_cancellation'] = 'required|string|max:255';
        }

        $request->validate($rules);

        abort_unless($order->status === 'pending', 422, 'A encomenda ja foi processada.');

        $order->status = $request->status;

        if ($request->status === 'canceled') {
            $order->reason_for_cancellation = $request->reason_for_cancellation;
        }

        $customerModel = Customer::find($order->customer_id);
        $customer = $customerModel ? $customerModel->user : null;

        if ($order->status === 'closed') {
            $this->generateReceiptAndNotify($order, $customer);
        } else {
            $order->save();
            if ($customer) {
                Mail::to($customer->email)->send(new OrderCanceledMail($order));
            }
        }

        return redirect()->route('orders.show', $order);
    }

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