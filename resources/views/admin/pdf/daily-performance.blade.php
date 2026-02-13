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
            /* menos arriba */
        }


        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            /* Mantiene todo arriba */
            margin-bottom: 20px;
        }

        header .logo img {
            height: 100px;
            /* Logo grande */
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
    </style>
</head>

<body>

    <h2>Resumen diario</h2>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
        <tr>
            <!-- LOGO -->
            <td style="width: 50%; text-align: left; vertical-align: top;">
                <img src="{{ public_path('images/sushi-logo.png') }}"
                    alt="Sushi Buffet Logo"
                    style="height: 90px;">
            </td>

            <!-- TEXTO DERECHA -->
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong style="font-size: 20px; color: #111;">Sushi Buffet</strong><br>
                <span style="font-size: 12px; color: #666;">
                    Informe diario de rendimiento
                </span><br>
                <span style="font-size: 12px; color: #666;">
                    {{ now()->format('d/m/Y') }}
                </span>
            </td>
        </tr>
    </table>


    <hr class="section-separator">

    <h2>Pedidos por hora</h2>

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

    <hr class="section-separator">

    <h2>Top reseñas del día</h2>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <!-- TOP PLATOS -->
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

            <!-- TOP BEBIDAS -->
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

    @if(file_exists(storage_path('app/public/charts/orders_hour.png')))
    @php
    $imgData = base64_encode(file_get_contents(storage_path('app/public/charts/orders_hour.png')));
    @endphp
    <div style="text-align:center; margin-bottom: 15px;">
        <img src="data:image/png;base64,{{ $imgData }}" style="width:100%; max-width:600px;">
    </div>
    @endif

    <div class="footer">
        Informe generado automáticamente por el sistema · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>