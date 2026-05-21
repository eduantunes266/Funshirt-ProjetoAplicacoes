<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Mail\OrderCanceledMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('id', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $items = OrderItem::where('order_id', $id)->get();

        return view('orders.show', compact('order', 'items'));
    }

  public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:closed,canceled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        $user = User::find($order->customer_id);

        if ($order->status === 'closed') {
            $items = OrderItem::where('order_id', $id)->get();
            $clientName = $user ? $user->name : 'Cliente';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('order', 'items', 'clientName'));
            
            $directory = storage_path('app/private/pdf_receipts');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $fileName = 'recibo_' . $order->id . '_' . time() . '.pdf';
            $pdfPath = $directory . '/' . $fileName;
            $pdf->save($pdfPath);

            $order->receipt_url = $fileName;
            $order->save();

            if ($user) {
                Mail::to($user->email)->send(new \App\Mail\OrderClosedMail($order, $pdfPath));
            }
        } else {
            $order->save();
            if ($user && $order->status === 'canceled') {
                Mail::to($user->email)->send(new OrderCanceledMail($order));
            }
        }

        return redirect()->route('orders.show', $id);
    }
}