<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rendimiento diario</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 8px 20px 20px 20px;
        }


        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        header .logo img {
            height: 100px;
            margin: 0;
            display: block;
        }

        header .title {
            text-align: right;
        }

        header .title strong {
            font-size: 20px;
            display: block;
            color: #111;
            margin-bottom: 2px;
        }

        header .title .label {
            font-size: 12px;
            color: #666;
            display: block;
        }

        h2 {
            margin-bottom: 10px;
            color: #111;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        .box {
            padding: 12px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background: #f9f9f9;
            font-weight: bold;
        }

        .label {
            font-size: 11px;
            color: #666;
        }

        .value {
            font-size: 20px;
            font-weight: bold;
            color: #111;
        }

        .section-separator {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .footer {
            font-size: 10px;
            color: #777;
            text-align: center;
            margin-top: 25px;
        }

        .section-card {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 22px;
        }

        .section-card h2 {
            font-size: 14px;
            margin: 0 0 10px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #ddd;
            color: #111;
        }

        .section-note {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .chart-title {
            font-size: 13px;
            font-weight: bold;
            color: #111;
            margin-bottom: 4px;
        }

        .chart-subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>

<body style="margin:0; padding:0;">

    <div style="position:relative; width:100%; height:100px;">

        <img src="{{ public_path('images/sushi-logo.png') }}"
            alt="Sushi Buffet"
            style="height:85px; display:block; position:absolute; top:0; left:0; margin:0; padding:0;">

        <div style="position:absolute; top:0; right:0; text-align:right;">
            <div style="font-size:20px; font-weight:bold; color:#111; margin:0; padding:0;">
                Sushi Buffet
            </div>
            <div style="font-size:12px; color:#666; margin:0; padding:0;">
                Informe diario de rendimiento
            </div>
            <div style="font-size:11px; color:#666; margin:0; padding:0;">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <hr class="section-separator">

    <div class="section-card">
        <h2>Top reseñas del día</h2>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <strong style="font-size: 14px; color: #111;">
                        Top platos reseñados hoy
                    </strong>

                    <table width="100%" style="margin-top: 8px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Plato</th>
                                <th style="text-align: right;">Reseñas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDishesToday as $dish)
                            <tr>
                                <td style="text-align: left;">
                                    {{ $dish->dish->name ?? '—' }}
                                </td>
                                <td style="text-align: right;">
                                    {{ $dish->total }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #777;">
                                    No hay reseñas de platos hoy
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>

                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    <strong style="font-size: 14px; color: #111;">
                        Top bebidas reseñadas hoy
                    </strong>

                    <table width="100%" style="margin-top: 8px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Bebida</th>
                                <th style="text-align: right;">Reseñas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDrinksToday as $drink)
                            <tr>
                                <td style="text-align: left;">
                                    {{ $drink->drink->name ?? '—' }}
                                </td>
                                <td style="text-align: right;">
                                    {{ $drink->total }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #777;">
                                    No hay reseñas de bebidas hoy
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    @if(file_exists(storage_path('app/public/charts/orders_hour.png')))
    @php
    $imgData = base64_encode(
    file_get_contents(storage_path('app/public/charts/orders_hour.png'))
    );
    @endphp

    <div class="section-card">
        <div class="chart-title">
            Evolución de pedidos por franja horaria
        </div>
        <div class="chart-subtitle">
            Número total de pedidos realizados en cada hora del día
        </div>

        <div style="text-align:center;">
            <img src="data:image/png;base64,{{ $imgData }}"
                style="width:100%; max-width:620px;">
        </div>
    </div>
    @endif

    <div class="section-card" style="page-break-inside: avoid;">
        <h2>Pedidos por hora</h2>
        <div class="section-note">
            Distribución del número de pedidos registrados durante el horario de apertura.
        </div>

        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Total pedidos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersPerHourLabels as $index => $hour)
                <tr>
                    <td>{{ $hour }}</td>
                    <td>{{ $ordersPerHourData[$index] }}</td>
                </tr>
                @endforeach
                <tr>
                    <th>Total</th>
                    <th>{{ array_sum($ordersPerHourData) }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Informe generado automáticamente por el sistema · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>