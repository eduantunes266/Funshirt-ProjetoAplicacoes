<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo #{{ $order->id }}</title>
    {{-- Estilos para o PDF --}}
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5; font-size: 12px; }
        .header { padding: 16px 20px; background: #4f46e5; color: #ffffff; border-radius: 6px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 12px; opacity: .9; }
        .meta-grid { width: 100%; margin-bottom: 24px; }
        .meta-grid td { padding: 4px 0; vertical-align: top; }
        .meta-grid .label { color: #6b7280; width: 130px; }
        h3 { margin: 0 0 10px; color: #111827; font-size: 14px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.items th, table.items td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        table.items th { background-color: #f3f4f6; color: #374151; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; }
        .total-box { margin-top: 20px; padding: 12px 16px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; text-align: right; }
        .total-box span { color: #047857; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 28px; text-align: center; color: #9ca3af; font-size: 10px; }
    </style>
</head>
<body>
    {{-- Cabeçalho do Documento --}}
    <div class="header">
        <h1>FunShirt</h1>
        <p>Recibo oficial de compra</p>
    </div>

    {{-- Informações da Encomenda --}}
    <table class="meta-grid">
        <tr><td class="label">Encomenda</td><td><strong>#{{ $order->id }}</strong></td></tr>
        <tr><td class="label">Data</td><td>{{ $order->date }}</td></tr>
        <tr><td class="label">Cliente</td><td>{{ $clientName }}</td></tr>
        <tr><td class="label">NIF</td><td>{{ $order->nif }}</td></tr>
        <tr><td class="label">Morada de envio</td><td>{{ $order->address }}</td></tr>
    </table>

    {{-- Tabela de Itens --}}
    <h3>Itens adquiridos</h3>
    <table class="items">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Cor</th>
                <th>Tamanho</th>
                <th>Qtd</th>
                <th>Preço unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->tshirtImage?->name ?? ('#' . $item->tshirt_image_id) }}</td>
                    <td>#{{ $item->color_code }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($item->sub_total, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Caixa de Total --}}
    <div class="total-box">
        Total pago: <span>{{ number_format($order->total_price, 2, ',', ' ') }} €</span>
    </div>

    {{-- Rodapé --}}
    <div class="footer">FunShirt · Documento gerado automaticamente</div>
</body>
</html>
