<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the reports dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get quick stats for the reports dashboard
        $totalRevenue = Rental::where('status', 'completed')->sum('total_cost');
        $monthlyRevenue = Rental::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_cost');

        $totalRentals = Rental::count();
        $activeRentals = Rental::whereIn('status', ['active', 'pending'])->count();

        // Get monthly revenue for the last 12 months for chart
        $monthlyRevenueData = $this->getMonthlyRevenue();

        // Get top 5 most rented cars
        $topCars = $this->getTopRentedCars(5);

        return view('admin.reports.index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'totalRentals',
            'activeRentals',
            'monthlyRevenueData',
            'topCars'
        ));
    }

    /**
     * Display the rentals report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function rentals(Request $request)
    {
        $query = Rental::with(['user', 'car']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }

        // Get rentals with pagination
        $rentals = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all cars for the filter dropdown
        $cars = Car::orderBy('model', 'asc')->get();

        return view('admin.reports.rentals', compact('rentals', 'cars'));
    }

    /**
     * Display the revenue report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function revenue(Request $request)
    {
        // Set default date range to current month if not provided
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        // Get daily revenue for the selected period
        $dailyRevenue = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_cost) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get revenue by car brand
        $brandRevenue = Rental::where('status', 'completed')
            ->whereBetween('rentals.created_at', [$startDate, $endDate])
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->selectRaw('cars.brand, SUM(rentals.total_cost) as revenue, COUNT(*) as count')
            ->groupBy('cars.brand')
            ->orderBy('revenue', 'desc')
            ->get();

        // Calculate total revenue for the period
        $totalRevenue = $dailyRevenue->sum('revenue');

        // Calculate average daily revenue
        $avgDailyRevenue = $dailyRevenue->count() > 0
            ? $totalRevenue / $dailyRevenue->count()
            : 0;

        return view('admin.reports.revenue', compact(
            'dailyRevenue',
            'brandRevenue',
            'totalRevenue',
            'avgDailyRevenue',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display the cars performance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function carsPerformance(Request $request)
    {
        // Set default date range to last 3 months if not provided
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subMonths(3);

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        // Get car performance data
        $carsPerformance = Car::withCount(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_sum_total_cost', 'desc')
            ->paginate(15);

        // Calculate utilization rate (days rented / total days available)
        $totalDays = $startDate->diffInDays($endDate) + 1;

        foreach ($carsPerformance as $car) {
            $daysRented = Rental::where('car_id', $car->id)
                ->whereIn('status', ['completed', 'active'])
                ->whereBetween('start_date', [$startDate, $endDate])
                ->get()
                ->sum(function($rental) use ($startDate, $endDate) {
                    $rentalStart = Carbon::parse($rental->start_date);
                    $rentalEnd = Carbon::parse($rental->end_date);

                    // Adjust dates to be within the report period
                    $effectiveStart = $rentalStart->lt($startDate) ? $startDate : $rentalStart;
                    $effectiveEnd = $rentalEnd->gt($endDate) ? $endDate : $rentalEnd;

                    return $effectiveStart->diffInDays($effectiveEnd) + 1;
                });

            $car->utilization_rate = $totalDays > 0 ? ($daysRented / $totalDays) * 100 : 0;
            $car->revenue_per_day = $daysRented > 0 ? ($car->rentals_sum_total_cost / $daysRented) : 0;
        }

        return view('admin.reports.cars-performance', compact(
            'carsPerformance',
            'startDate',
            'endDate',
            'totalDays'
        ));
    }

    /**
     * Display the customer analysis report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function customerAnalysis(Request $request)
    {
        // Get top customers by rental count
        $topCustomersByCount = User::where('role', 'customer')
            ->withCount('rentals')
            ->withSum(['rentals' => function($query) {
                $query->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_count', 'desc')
            ->take(10)
            ->get();

        // Get top customers by revenue
        $topCustomersByRevenue = User::where('role', 'customer')
            ->withCount('rentals')
            ->withSum(['rentals' => function($query) {
                $query->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_sum_total_cost', 'desc')
            ->take(10)
            ->get();

        // Get new customers per month for the last 12 months
        $newCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format for chart
        $newCustomersData = [];
        foreach ($newCustomers as $data) {
            $date = Carbon::createFromDate($data->year, $data->month, 1);
            $newCustomersData[] = [
                'month' => $date->format('M Y'),
                'count' => $data->count
            ];
        }

        // Get customer retention rate
        $totalCustomers = User::where('role', 'customer')->count();
        $repeatCustomers = User::where('role', 'customer')
            ->whereHas('rentals', function($query) {
                $query->havingRaw('COUNT(*) > 1');
            })
            ->count();

        $retentionRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;

        return view('admin.reports.customer-analysis', compact(
            'topCustomersByCount',
            'topCustomersByRevenue',
            'newCustomersData',
            'totalCustomers',
            'repeatCustomers',
            'retentionRate'
        ));
    }

    /**
     * Generate a PDF report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request)
    {
        $reportType = $request->report_type;
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();

        switch ($reportType) {
            case 'rentals':
                $data = $this->getRentalsReportData($startDate, $endDate);
                $view = 'admin.reports.pdf.rentals';
                $title = 'Rentals Report';
                break;

            case 'revenue':
                $data = $this->getRevenueReportData($startDate, $endDate);
                $view = 'admin.reports.pdf.revenue';
                $title = 'Revenue Report';
                break;

            case 'cars':
                $data = $this->getCarsReportData($startDate, $endDate);
                $view = 'admin.reports.pdf.cars';
                $title = 'Cars Performance Report';
                break;

            case 'customers':
                $data = $this->getCustomersReportData($startDate, $endDate);
                $view = 'admin.reports.pdf.customers';
                $title = 'Customer Analysis Report';
                break;

            default:
                return response()->make('Invalid report type', 400);
        }

        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['generated_at'] = Carbon::now();

        $pdf = PDF::loadView($view, $data);

        return $pdf->download($title . ' - ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d') . '.pdf');
    }

    /**
     * Export report data to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->report_type;
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();

        switch ($reportType) {
            case 'rentals':
                return $this->exportRentalsCsv($startDate, $endDate);

            case 'revenue':
                return $this->exportRevenueCsv($startDate, $endDate);

            case 'cars':
                return $this->exportCarsCsv($startDate, $endDate);

            case 'customers':
                return $this->exportCustomersCsv($startDate, $endDate);

            default:
                return response('Invalid report type', 400);
        }
    }

    /**
     * Get monthly revenue data for the last 12 months.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getMonthlyRevenue()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyRevenue = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_cost) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format for chart
        $result = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;

            $revenue = $monthlyRevenue
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            $result[] = [
                'month' => $currentDate->format('M Y'),
                'revenue' => $revenue ? $revenue->revenue : 0
            ];

            $currentDate->addMonth();
        }

        return collect($result);
    }

    /**
     * Get top rented cars.
     *
     * @param  int  $limit
     * @return \Illuminate\Support\Collection
     */
    private function getTopRentedCars($limit = 5)
    {
        return Car::withCount('rentals')
            ->withSum(['rentals' => function($query) {
                $query->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get data for rentals report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getRentalsReportData($startDate, $endDate)
    {
        $rentals = Rental::with(['user', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $statusCounts = $rentals->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'rentals' => $rentals,
            'total_count' => $rentals->count(),
            'status_counts' => $statusCounts,
            'total_revenue' => $rentals->where('status', 'completed')->sum('total_cost')
        ];
    }

    /**
     * Get data for revenue report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getRevenueReportData($startDate, $endDate)
    {
        $dailyRevenue = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_cost) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $brandRevenue = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->selectRaw('cars.brand, SUM(rentals.total_cost) as revenue, COUNT(*) as count')
            ->groupBy('cars.brand')
            ->orderBy('revenue', 'desc')
            ->get();

        $totalRevenue = $dailyRevenue->sum('revenue');

        return [
            'daily_revenue' => $dailyRevenue,
            'brand_revenue' => $brandRevenue,
            'total_revenue' => $totalRevenue,
            'avg_daily_revenue' => $dailyRevenue->count() > 0 ? $totalRevenue / $dailyRevenue->count() : 0
        ];
    }

    /**
     * Get data for cars performance report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getCarsReportData($startDate, $endDate)
    {
        $carsPerformance = Car::withCount(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_sum_total_cost', 'desc')
            ->get();

        $totalDays = $startDate->diffInDays($endDate) + 1;

        foreach ($carsPerformance as $car) {
            $daysRented = Rental::where('car_id', $car->id)
                ->whereIn('status', ['completed', 'active'])
                ->whereBetween('start_date', [$startDate, $endDate])
                ->get()
                ->sum(function($rental) use ($startDate, $endDate) {
                    $rentalStart = Carbon::parse($rental->start_date);
                    $rentalEnd = Carbon::parse($rental->end_date);

                    $effectiveStart = $rentalStart->lt($startDate) ? $startDate : $rentalStart;
                    $effectiveEnd = $rentalEnd->gt($endDate) ? $endDate : $rentalEnd;

                    return $effectiveStart->diffInDays($effectiveEnd) + 1;
                });

            $car->utilization_rate = $totalDays > 0 ? ($daysRented / $totalDays) * 100 : 0;
            $car->revenue_per_day = $daysRented > 0 ? ($car->rentals_sum_total_cost / $daysRented) : 0;
            $car->days_rented = $daysRented;
        }

        return [
            'cars_performance' => $carsPerformance,
            'total_days' => $totalDays,
            'total_revenue' => $carsPerformance->sum('rentals_sum_total_cost'),
            'total_rentals' => $carsPerformance->sum('rentals_count')
        ];
    }

    /**
     * Get data for customers report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getCustomersReportData($startDate, $endDate)
    {
        $topCustomers = User::where('role', 'customer')
            ->withCount(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['rentals' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            }], 'total_cost')
            ->orderBy('rentals_sum_total_cost', 'desc')
            ->take(20)
            ->get();

        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $activeCustomers = User::where('role', 'customer')
            ->whereHas('rentals', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->count();

        $totalCustomers = User::where('role', 'customer')
            ->where('created_at', '<=', $endDate)
            ->count();

        return [
            'top_customers' => $topCustomers,
            'new_customers' => $newCustomers,
            'active_customers' => $activeCustomers,
            'total_customers' => $totalCustomers,
            'total_revenue' => $topCustomers->sum('rentals_sum_total_cost'),
            'total_rentals' => $topCustomers->sum('rentals_count')
        ];
    }

    /**
     * Export rentals data to CSV.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Http\Response
     */
    private function exportRentalsCsv($startDate, $endDate)
    {
        $rentals = Rental::with(['user', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rentals_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($rentals) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Customer', 'Email', 'Car', 'Start Date', 'End Date',
                'Status', 'Total Amount', 'Created At'
            ]);

            // Add data
            foreach ($rentals as $rental) {
                fputcsv($file, [
                    $rental->id,
                    $rental->user->name,
                    $rental->user->email,
                    $rental->car->make . ' ' . $rental->car->model . ' (' . $rental->car->year . ')',
                    $rental->start_date,
                    $rental->end_date,
                    $rental->status,
                    $rental->total_cost,
                    $rental->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export revenue data to CSV.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Http\Response
     */
    private function exportRevenueCsv($startDate, $endDate)
    {
        $dailyRevenue = Rental::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_cost) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="revenue_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($dailyRevenue) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Date', 'Revenue', 'Number of Rentals', 'Average Revenue per Rental']);

            // Add data
            foreach ($dailyRevenue as $data) {
                fputcsv($file, [
                    $data->date,
                    $data->revenue,
                    $data->count,
                    $data->count > 0 ? $data->revenue / $data->count : 0
                ]);
            }

            // Add total
            fputcsv($file, [
                'Total',
                $dailyRevenue->sum('revenue'),
                $dailyRevenue->sum('count'),
                $dailyRevenue->sum('count') > 0 ? $dailyRevenue->sum('revenue') / $dailyRevenue->sum('count') : 0
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export cars performance data to CSV.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Http\Response
     */
    private function exportCarsCsv($startDate, $endDate)
    {
        $data = $this->getCarsReportData($startDate, $endDate);
        $carsPerformance = $data['cars_performance'];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cars_performance_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($carsPerformance) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Make', 'Model', 'Year', 'brand', 'License Plate',
                'Number of Rentals', 'Total Revenue', 'Days Rented',
                'Utilization Rate (%)', 'Revenue per Day'
            ]);

            // Add data
            foreach ($carsPerformance as $car) {
                fputcsv($file, [
                    $car->id,
                    $car->make,
                    $car->model,
                    $car->year,
                    $car->brand,
                    $car->license_plate,
                    $car->rentals_count,
                    $car->rentals_sum_total_cost,
                    $car->days_rented,
                    number_format($car->utilization_rate, 2),
                    number_format($car->revenue_per_day, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export customers data to CSV.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Http\Response
     */
    private function exportCustomersCsv($startDate, $endDate)
    {
        $data = $this->getCustomersReportData($startDate, $endDate);
        $topCustomers = $data['top_customers'];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($topCustomers) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Number of Rentals',
                'Total Spent', 'Average Spent per Rental', 'Registration Date'
            ]);

            // Add data
            foreach ($topCustomers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->rentals_count,
                    $customer->rentals_sum_total_cost,
                    $customer->rentals_count > 0 ? $customer->rentals_sum_total_cost / $customer->rentals_count : 0,
                    $customer->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
