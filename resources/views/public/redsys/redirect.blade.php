@extends('layouts.public')

@section('title', 'Redirigiendo a Redsys')

@section('header-title', 'Procesando pago')

@section('content')
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Redirigiendo al pago con tarjeta…</h2>
            <p class="text-sm text-gray-600 mb-6">En unos segundos se abrirá la pasarela segura de Redsys.</p>

            <form id="redsysForm" method="POST" action="{{ $actionUrl }}">
                <input type="hidden" name="Ds_SignatureVersion" value="{{ $signatureVersion }}">
                <input type="hidden" name="Ds_MerchantParameters" value="{{ $merchantParameters }}">
                <input type="hidden" name="Ds_Signature" value="{{ $signature }}">
                <noscript>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Continuar a Redsys
                    </button>
                </noscript>
            </form>

            <div class="mt-6 text-xs text-gray-500">
                Si no se redirige automáticamente, pulsa el botón.
            </div>
        </div>
    </div>

    <script>
        document.getElementById('redsysForm')?.submit();
    </script>
@endsection

