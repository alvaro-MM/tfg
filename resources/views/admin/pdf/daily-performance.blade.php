<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Rendimiento diario</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1,
        h2 {
            margin-bottom: 8px;
            color: #111;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f4f4f4;
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
            border-top: 1px solid #eee;
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

    <table width="100%" style="margin-bottom: 15px;">
        <tr>
            <td style="text-align: left;">
                <strong style="font-size: 16px;">Sushi Buffet</strong><br>
                <span class="label">Informe diario de rendimiento</span>
            </td>
            <td style="text-align: right;" class="label">
                {{ now()->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <hr class="section-separator">

    <h2>Resumen general</h2>

    <table width="100%" style="margin-bottom: 15px;">
        <tr>
            <td class="box" style="width: 33%;">
                <div class="label">Usuarios</div>
                <div class="value">{{ $usersToday }}</div>
            </td>
            <td class="box" style="width: 33%;">
                <div class="label">Pedidos</div>
                <div class="value">{{ $ordersToday }}</div>
            </td>
            <td class="box" style="width: 33%;">
                <div class="label">Reseñas</div>
                <div class="value">{{ $reviewsToday }}</div>
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

    <div class="footer">
        Informe generado automáticamente por el sistema · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>