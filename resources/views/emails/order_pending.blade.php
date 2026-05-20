<!DOCTYPE html>
<html>
<head>
    <title>Encomenda Pendente</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #0056b3;">Olá!</h2>
    <p>Obrigado por comprar na FunShirt. A sua encomenda #{{ $order->id }} foi registada com sucesso e encontra-se no estado <strong>Pendente</strong>.</p>
    <p>Estamos a preparar tudo para iniciar o processamento (estampagem e envio).</p>
    <p><strong>Detalhes:</strong></p>
    <ul>
        <li>Data: {{ $order->date }}</li>
        <li>Total: {{ number_format($order->total_price, 2, ',', ' ') }} €</li>
    </ul>
    <p>Iremos notificá-lo novamente assim que a sua encomenda for enviada (Fechada).</p>
    <p>Com os melhores cumprimentos,<br>A Equipa FunShirt</p>
</body>
</html>