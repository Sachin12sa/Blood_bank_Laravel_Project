<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;

class AdminDashboardController extends Controller
{
    public function index()
    {
        
        return view('admin.dashboard', [
            'totalDonors'      => Donor::count(),
            'totalHospitals'   => Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => BloodRequest::where('status', 'pending')->count(),
            'inventory'        => BloodInventory::all(),
        ]);
    }
}