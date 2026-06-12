<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

/**
 * Classe responsável por configurar e enviar o e-mail de notificação quando uma encomenda é Enviada (Fechada),
 * incluindo o recibo em formato PDF em anexo.
 */
class OrderClosedMail extends Mailable
{
    // Permite uso em Filas (Queues) para envio assíncrono
    use Queueable, SerializesModels;

    public $order;
    // Caminho local para o PDF gerado, guardado temporariamente ou de forma persistente
    protected $pdfPath;

    /**
     * Construtor: Recebe a instância da encomenda e o caminho físico do ficheiro PDF do recibo.
     */
    public function __construct(Order $order, $pdfPath)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Define o assunto e outros dados de cabeçalho.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A sua encomenda FunShirt foi enviada! (Recibo em anexo)',
        );
    }

    /**
     * Define o template (view do Blade) usado para renderizar a mensagem do e-mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_closed',
        );
    }

    /**
     * Define os ficheiros que devem ser enviados como anexo neste e-mail.
     * Pega no PDF do caminho fornecido e renomeia-o para exibição ("recibo_{ID}.pdf").
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('recibo_' . $this->order->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}