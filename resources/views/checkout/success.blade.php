@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 40px auto; text-align: center; background-color: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <h1 style="color: #28a745; font-size: 28px; margin-bottom: 15px;">Encomenda Submetida com Sucesso!</h1>
        <p style="color: #555; font-size: 16px; margin-bottom: 30px;">Obrigado pela sua compra. A sua encomenda foi registada no nosso sistema com o estado pendente.</p>
        <a href="{{ url('/') }}" style="background-color: #0056b3; color: white; font-weight: bold; padding: 12px 24px; border-radius: 4px; text-decoration: none; font-size: 16px;">
            Voltar ao Catálogo
        </a>
    </div>
@endsection