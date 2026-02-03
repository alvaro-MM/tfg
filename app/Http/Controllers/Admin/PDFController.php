<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;

class PDFController extends Controller
{
    public function dailyPerformance()
    {
        $today = Carbon::today();

        $usersToday   = User::whereDate('created_at', $today)->count();
        $ordersToday  = Order::whereDate('created_at', $today)->count();
        $reviewsToday = Review::whereDate('created_at', $today)->count();

        $openHours = array_merge(range(12, 16), range(19, 23));

        $ordersPerHour = Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->get();

        $ordersPerHourLabels = [];
        $ordersPerHourData   = [];

        foreach ($openHours as $hour) {
            $ordersPerHourLabels[] = sprintf('%02d:00', $hour);
            $ordersPerHourData[$hour] = 0;
        }

        foreach ($ordersPerHour as $row) {
            if (isset($ordersPerHourData[$row->hour])) {
                $ordersPerHourData[$row->hour] = $row->total;
            }
        }

        $ordersPerHourData = array_values($ordersPerHourData);

        $pdf = Pdf::loadView('admin.pdf.daily-performance', compact(
            'usersToday',
            'ordersToday',
            'reviewsToday',
            'ordersPerHourLabels',
            'ordersPerHourData'
        ))->setPaper('a4', 'portrait');

        return $pdf->download(
            'rendimiento_diario_' . now()->format('d-m-Y') . '.pdf'
        );
    }
}
