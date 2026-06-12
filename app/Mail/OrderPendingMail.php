<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Classe responsável por configurar e enviar o e-mail de confirmação de que a encomenda foi recebida com sucesso (Pendente).
 */
class OrderPendingMail extends Mailable
{
    // Permite que o envio de e-mails possa ser processado de forma assíncrona
    use Queueable, SerializesModels;

    // A encomenda a enviar na mensagem, fica disponível na view do e-mail
    public $order;

    /**
     * Construtor: Recebe e armazena a instância da encomenda para posterior renderização.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Define as meta-informações do e-mail, como o assunto.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A sua encomenda FunShirt está em processamento (Pendente)',
        );
    }

    /**
     * Aponta para o ficheiro Blade que vai gerar o conteúdo visual/HTML deste e-mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_pending',
        );
    }
}