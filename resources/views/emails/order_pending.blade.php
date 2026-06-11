<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Encomenda Pendente</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; background: #f8fafc; padding: 24px;">
    {{-- Contentor Principal --}}
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
        
        {{-- Cabeçalho do Email --}}
        <div style="background: linear-gradient(90deg, #4f46e5, #c026d3); color: #ffffff; padding: 20px 24px;">
            <h1 style="margin: 0; font-size: 22px;">FunShirt</h1>
            <p style="margin: 4px 0 0; font-size: 13px; opacity: .9;">A sua encomenda foi recebida</p>
        </div>
        
        {{-- Corpo do Email --}}
        <div style="padding: 24px;">
            <h2 style="margin: 0 0 12px; color: #111827; font-size: 18px;">Obrigado pela sua compra!</h2>
            <p style="margin: 0 0 12px;">A encomenda <strong>#{{ $order->id }}</strong> foi registada com sucesso e encontra-se no estado <strong style="color: #b45309;">Pendente</strong>.</p>
            <p style="margin: 0 0 16px;">Estamos a preparar tudo para iniciar o processamento (estampagem e envio).</p>

            {{-- Resumo da Encomenda --}}
            <table style="width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 14px;">
                <tr><td style="padding: 6px 0; color: #6b7280;">Data</td><td style="padding: 6px 0; text-align: right;">{{ $order->date }}</td></tr>
                <tr><td style="padding: 6px 0; color: #6b7280;">Total</td><td style="padding: 6px 0; text-align: right; font-weight: bold;">{{ number_format($order->total_price, 2, ',', ' ') }} €</td></tr>
            </table>

            {{-- Despedida --}}
            <p style="margin: 16px 0 0; color: #6b7280; font-size: 13px;">Iremos notificá-lo novamente quando a sua encomenda for enviada.</p>
            <p style="margin: 16px 0 0;">Com os melhores cumprimentos,<br><strong>A Equipa FunShirt</strong></p>
        </div>
    </div>
</body>
</html>
