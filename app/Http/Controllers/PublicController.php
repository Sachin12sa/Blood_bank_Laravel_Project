<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BloodInventory;
use App\Models\Campaign;

class PublicController extends Controller
{
    public function index()
    {
        $stats = [
            'donors' => \App\Models\Donor::count(),
            'hospitals' => \App\Models\Hospital::count(),
            'blood_units' => \App\Models\BloodUnit::available()->count(),
            'requests_fulfilled' => \App\Models\BloodRequest::where('status', 'dispatched')->count()
        ];
        
        $campaigns = Campaign::where('status', 'upcoming')
            ->orWhere('status', 'active')
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();
            
        return view('welcome', compact('stats', 'campaigns'));
    }

    public function searchBlood(Request $request)
    {
        $request->validate([
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-'
        ]);

        $inventory = BloodInventory::where('blood_group', $request->blood_group)->first();
        
        return response()->json([
            'blood_group' => $request->blood_group,
            'available_units' => $inventory ? $inventory->available_units : 0
        ]);
    }
}
