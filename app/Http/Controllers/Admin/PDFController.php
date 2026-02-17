<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function dailyPerformance()
    {
        $today = Carbon::today();

        $usersToday   = User::whereDate('created_at', $today)->count();
        $reviewsToday = Review::whereDate('created_at', $today)->count();

        $openHours = array_merge(range(12, 16), range(19, 23));

        $ordersPerHour = $this->getOrdersPerHour($today);
        $ordersPerHourLabels = [];
        $ordersPerHourData = [];

        foreach ($openHours as $hour) {
            $ordersPerHourLabels[] = sprintf('%02d:00', $hour);
            $ordersPerHourData[$hour] = $ordersPerHour[$hour] ?? 0;
        }

        $ordersPerHourData = array_values($ordersPerHourData);

        $this->generateOrdersHourChart($ordersPerHourLabels, $ordersPerHourData);

        $ordersToday = array_sum($ordersPerHourData);

        $topDishesToday = Review::selectRaw('dish_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->whereNotNull('dish_id')
            ->groupBy('dish_id')
            ->orderByDesc('total')
            ->with('dish')
            ->take(3)
            ->get();

        $topDrinksToday = Review::selectRaw('drink_id, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->whereNotNull('drink_id')
            ->groupBy('drink_id')
            ->orderByDesc('total')
            ->with('drink')
            ->take(3)
            ->get();

        $pdf = Pdf::loadView('admin.pdf.daily-performance', compact(
            'usersToday',
            'ordersToday',
            'reviewsToday',
            'ordersPerHourLabels',
            'ordersPerHourData',
            'topDishesToday',
            'topDrinksToday'
        ))->setPaper('a4', 'portrait');

        return $pdf->download(
            'rendimiento_diario_' . now()->format('d-m-Y') . '.pdf'
        );
    }

    private function getOrdersPerHour($date)
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $orders = Order::selectRaw(
                "CAST(strftime('%H', created_at) AS INTEGER) as hour, COUNT(*) as total"
            )
                ->whereDate('created_at', $date)
                ->groupBy('hour')
                ->get();
        } else {
            $orders = Order::selectRaw(
                "HOUR(created_at) as hour, COUNT(*) as total"
            )
                ->whereDate('created_at', $date)
                ->groupBy('hour')
                ->get();
        }

        $result = [];
        foreach ($orders as $row) {
            $result[$row->hour] = $row->total;
        }

        return $result;
    }

    private function generateOrdersHourChart(array $labels, array $data)
    {
        Storage::disk('public')->makeDirectory('charts');

        $path = 'charts/orders_hour.png';
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $maxValue = max($data);
        $suggestedMax = max(5, $maxValue + 1);

        $chartConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Pedidos por hora',
                    'data' => $data,
                    'borderColor' => '#4F46E5',
                    'backgroundColor' => 'rgba(79,70,229,0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                    'pointRadius' => 3
                ]]
            ],
            'options' => [
                'legend' => ['display' => false],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true,
                            'stepSize' => 1,
                            'suggestedMax' => $suggestedMax
                        ]
                    ]]
                ]
            ]
        ];

        $url = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
        $imageData = file_get_contents($url);

        Storage::disk('public')->put($path, $imageData);
    }
}
