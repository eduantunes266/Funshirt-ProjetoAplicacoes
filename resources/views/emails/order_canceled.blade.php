<!DOCTYPE html>
<html>
<head>
    <title>Encomenda Anulada</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #dc3545;">Olá!</h2>
    <p>Lamentamos informar, mas a sua encomenda #{{ $order->id }} foi <strong>Anulada</strong> e não prosseguirá para processamento.</p>
    @if($order->reason_for_cancellation)
        <p><strong>Motivo da anulação:</strong> {{ $order->reason_for_cancellation }}</p>
    @endif
    <p>Se necessitar de esclarecimentos adicionais, por favor contacte o nosso suporte.</p>
    <p>Com os melhores cumprimentos,<br>A Equipa FunShirt</p>
</body>
</html>