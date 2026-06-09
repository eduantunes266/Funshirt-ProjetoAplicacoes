<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Encomenda Anulada</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; background: #f8fafc; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
        <div style="background: linear-gradient(90deg, #dc2626, #ef4444); color: #ffffff; padding: 20px 24px;">
            <h1 style="margin: 0; font-size: 22px;">FunShirt</h1>
            <p style="margin: 4px 0 0; font-size: 13px; opacity: .9;">Atualização da sua encomenda</p>
        </div>
        <div style="padding: 24px;">
            <h2 style="margin: 0 0 12px; color: #111827; font-size: 18px;">Encomenda anulada</h2>
            <p style="margin: 0 0 12px;">Lamentamos informar que a sua encomenda <strong>#{{ $order->id }}</strong> foi <strong style="color: #b91c1c;">Anulada</strong> e não prosseguirá para processamento.</p>
            @if($order->reason_for_cancellation)
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 14px; margin: 12px 0;">
                    <strong style="display: block; color: #b91c1c; margin-bottom: 4px;">Motivo</strong>
                    <span>{{ $order->reason_for_cancellation }}</span>
                </div>
            @endif
            <p style="margin: 16px 0 0;">Se necessitar de esclarecimentos, contacte o nosso suporte.</p>
            <p style="margin: 16px 0 0;">Com os melhores cumprimentos,<br><strong>A Equipa FunShirt</strong></p>
        </div>
    </div>
</body>
</html>
