<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Encomenda Concluída</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; background: #f8fafc; padding: 24px;">
    {{-- Contentor Principal --}}
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
        
        {{-- Cabeçalho do Email --}}
        <div style="background: linear-gradient(90deg, #059669, #10b981); color: #ffffff; padding: 20px 24px;">
            <h1 style="margin: 0; font-size: 22px;">FunShirt</h1>
            <p style="margin: 4px 0 0; font-size: 13px; opacity: .9;">A sua encomenda foi enviada</p>
        </div>
        
        {{-- Corpo do Email --}}
        <div style="padding: 24px;">
            <h2 style="margin: 0 0 12px; color: #111827; font-size: 18px;">A sua encomenda está a caminho!</h2>
            <p style="margin: 0 0 12px;">A encomenda <strong>#{{ $order->id }}</strong> foi processada, estampada e enviada com sucesso.</p>
            <p style="margin: 0 0 12px;">Em anexo enviamos o recibo oficial em formato PDF.</p>
            
            {{-- Despedida --}}
            <p style="margin: 16px 0 0;">Agradecemos a sua preferência.</p>
            <p style="margin: 16px 0 0;">Com os melhores cumprimentos,<br><strong>A Equipa FunShirt</strong></p>
        </div>
    </div>
</body>
</html>
