<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderClosedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    protected $pdfPath;

    public function __construct(Order $order, $pdfPath)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A sua encomenda FunShirt foi enviada! (Recibo em anexo)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_closed',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('recibo_' . $this->order->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}