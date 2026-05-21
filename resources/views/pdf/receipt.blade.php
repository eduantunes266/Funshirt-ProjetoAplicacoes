<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FunShirt</h1>
        <p>Recibo Oficial de Compra</p>
    </div>
    <div class="details">
        <p><strong>Encomenda:</strong> #{{ $order->id }}</p>
        <p><strong>Data:</strong> {{ $order->date }}</p>
        <p><strong>Nome do Cliente:</strong> {{ $clientName }}</p>
        <p><strong>NIF:</strong> {{ $order->nif }}</p>
        <p><strong>Morada de Envio:</strong> {{ $order->address }}</p>
    </div>
    <h3>Itens Adquiridos</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID Imagem</th>
                <th>Cor</th>
                <th>Tamanho</th>
                <th>Qtd</th>
                <th>Preço Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->tshirt_image_id }}</td>
                    <td>{{ $item->color_code }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($item->sub_total, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        Total Pago: {{ number_format($order->total_price, 2, ',', ' ') }} €
    </div>
</body>
</html>