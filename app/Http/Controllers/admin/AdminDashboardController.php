<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\Donation;
use App\Models\BloodUnit;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'totalDonors'      => Donor::count(),
            'totalHospitals'   => Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => BloodRequest::where('status', 'pending')->count(),
            'availableUnits'   => BloodUnit::available()->count(),
            'expiringUnits'    => BloodUnit::available()->expiringSoon(7)->count(),
        ];

        // Inventory Chart Data (Bar Chart)
        $inventory = BloodInventory::orderBy('blood_group')->get();
        $chartData = [
            'labels' => $inventory->pluck('blood_group'),
            'data'   => $inventory->pluck('available_units'),
            'colors' => $inventory->pluck('blood_group')->map(fn($g) => '#C0152A'),
        ];

        // Request Trends (Monthly - Line Chart)
        $requestTrends = BloodRequest::select(
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('count(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTHNAME(created_at)'))
        ->orderByRaw('MIN(created_at)')
        ->get();

        $trendData = [
            'labels' => $requestTrends->pluck('month'),
            'data'   => $requestTrends->pluck('count'),
        ];

        // Recent Activity
        $recentDonations = Donation::with('donor.user')->latest()->take(5)->get();
        $recentRequests  = BloodRequest::with('hospital.user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'trendData', 'recentDonations', 'recentRequests', 'inventory'));
    }

    public function reports()
    {
        $groupStats = BloodInventory::all();
        
        $requestSummary = [
            'total'      => BloodRequest::count(),
            'pending'    => BloodRequest::where('status', 'pending')->count(),
            'approved'   => BloodRequest::where('status', 'approved')->count(),
            'dispatched' => BloodRequest::where('status', 'dispatched')->count(),
            'rejected'   => BloodRequest::where('status', 'rejected')->count(),
        ];

        $urgencyStats = [
            'normal'   => BloodRequest::where('urgency', 'normal')->count(),
            'critical' => BloodRequest::where('urgency', 'critical')->count(),
        ];

        $monthlyDonations = Donation::select(
            DB::raw('MONTHNAME(donated_at) as month'),
            DB::raw('count(*) as count')
        )
        ->whereYear('donated_at', date('Y'))
        ->groupBy(DB::raw('MONTHNAME(donated_at)'))
        ->orderByRaw('MIN(donated_at)')
        ->get();

        return view('admin.reports', compact('groupStats', 'requestSummary', 'urgencyStats', 'monthlyDonations'));
    }
}