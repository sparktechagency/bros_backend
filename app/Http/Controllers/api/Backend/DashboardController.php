<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $earnings     = Booking::whereMonth('created_at', now())->sum('price');
        $appointments = Booking::whereDate('booking_date', now())->count();
        $users        = User::where('role', 'USER')->count();

        // booking_statistics
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $days = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];

        $data = Booking::select(
            DB::raw('DATE_FORMAT(created_at, "%a") as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
             ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');

        $booking_statistics = collect();
        foreach ($days as $day) {
            $booking_statistics->push([
                'day'   => $day,
                'total' => $data->get($day, 0),
            ]);
        }


        // earning_statistics
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $days = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];

        $data = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%a") as day'),
                DB::raw('SUM(price) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
             ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $earning_statistics = collect();
        $weeklyTotal = 0;

        foreach ($days as $day) {
            $total = $data->get($day)->total ?? 0;
            $count = $data->get($day)->count ?? 0;

            $earning_statistics->push([
                'day'   => $day,
                'total' => round($total, 2),
            ]);

            $weeklyTotal += $total;
        }

        $data=[
            'earnings'=>round($earnings,2),
            'appointments'=>$appointments,
            'users'=>$users,
            'earning_statistics_total'=>round($weeklyTotal,2),
            'booking_statistics'=>$booking_statistics,
            'earning_statistics'=>$earning_statistics,
        ];
        return response()->json([
            'status'  => true,
            'message' => 'Data retreived successfully',
            'data'    => $data,
        ]);

    }
}
