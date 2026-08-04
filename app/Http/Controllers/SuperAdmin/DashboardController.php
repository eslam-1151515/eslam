<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\SubscriptionReceipt;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalStores = Tenant::count();
        $totalSubscriptions = Subscription::where('status', 'active')->count();
        $pendingPayments = SubscriptionReceipt::where('status', 'pending')->count();

        // Database driver check for group-by month formatting
        $driver = DB::connection()->getDriverName();
        $monthFormat = $driver === 'sqlite' 
            ? "strftime('%Y-%m', created_at)" 
            : "DATE_FORMAT(created_at, '%Y-%m')";
            
        $approvedMonthFormat = $driver === 'sqlite' 
            ? "strftime('%Y-%m', approved_at)" 
            : "DATE_FORMAT(approved_at, '%Y-%m')";

        // Monthly store registrations
        $registrationsOverTime = Tenant::select(
            DB::raw("$monthFormat as month"),
            DB::raw("count(*) as count")
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->take(12)
        ->get();

        // Monthly approved receipt amounts
        $revenueOverTime = SubscriptionReceipt::select(
            DB::raw("$approvedMonthFormat as month"),
            DB::raw("sum(amount) as total_amount")
        )
        ->where('status', 'approved')
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->take(12)
        ->get();

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => [
                'total_stores' => $totalStores,
                'total_subscriptions' => $totalSubscriptions,
                'pending_payments' => $pendingPayments,
            ],
            'graphs' => [
                'registrations' => $registrationsOverTime,
                'revenue' => $revenueOverTime,
            ]
        ]);
    }
}
