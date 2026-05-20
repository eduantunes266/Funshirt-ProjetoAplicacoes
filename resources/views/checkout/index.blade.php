@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <h1 style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">Detalhes de Faturação e Pagamento</h1>

        <form action="{{ route('checkout.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <div>
                <label for="nif" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">NIF</label>
                <input type="text" name="nif" id="nif" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                @error('nif')
                    <p style="color: #dc3545; font-size: 13px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Morada de Envio</label>
                <input type="text" name="address" id="address" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                @error('address')
                    <p style="color: #dc3545; font-size: 13px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">{{ $message }}</p>
                @enderror
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 10px 0;">

            <div>
                <label for="payment_type" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Método de Pagamento</label>
                <select name="payment_type" id="payment_type" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                    <option value="Visa">Visa</option>
                    <option value="PayPal">PayPal</option>
                    <option value="MB WAY">MB WAY</option>
                </select>
                @error('payment_type')
                    <p style="color: #dc3545; font-size: 13px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label id="payment_ref_label" for="payment_ref" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Número do Cartão Visa</label>
                <input type="text" name="payment_ref" id="payment_ref" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                @error('payment_ref')
                    <p style="color: #dc3545; font-size: 13px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" style="width: 100%; background-color: #28a745; color: white; font-weight: bold; padding: 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 18px;">
                    Confirmar Encomenda e Pagar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentType = document.getElementById('payment_type');
            const paymentRefLabel = document.getElementById('payment_ref_label');
            const paymentRefInput = document.getElementById('payment_ref');

            function updatePaymentField() {
                if (paymentType.value === 'MB WAY') {
                    paymentRefLabel.innerText = 'Número de Telemóvel';
                    paymentRefInput.placeholder = 'Ex: 912345678';
                    paymentRefInput.type = 'number';
                } else if (paymentType.value === 'Visa') {
                    paymentRefLabel.innerText = 'Número do Cartão Visa';
                    paymentRefInput.placeholder = 'Ex: 4000123456789010';
                    paymentRefInput.type = 'number';
                } else if (paymentType.value === 'PayPal') {
                    paymentRefLabel.innerText = 'Email da Conta PayPal';
                    paymentRefInput.placeholder = 'Ex: cliente@email.com';
                    paymentRefInput.type = 'email';
                }
            }

            paymentType.addEventListener('change', updatePaymentField);
            updatePaymentField();
        });
    </script>
@endsection