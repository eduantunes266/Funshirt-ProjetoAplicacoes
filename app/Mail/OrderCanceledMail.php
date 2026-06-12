<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Classe responsável por configurar e enviar o e-mail de notificação quando uma encomenda é Anulada.
 */
class OrderCanceledMail extends Mailable
{
    // Permite que o envio de e-mails seja colocado numa fila (Queue) para processamento assíncrono
    use Queueable, SerializesModels;

    // A encomenda a ser cancelada (disponível publicamente para acesso na view do e-mail)
    public $order;

    /**
     * Construtor: Recebe a instância da encomenda no momento da criação do Mail.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Define as informações de cabeçalho do e-mail, como o assunto.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A sua encomenda FunShirt foi anulada',
        );
    }

    /**
     * Define a View do Blade que contém o corpo (HTML) deste e-mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_canceled',
        );
    }
}