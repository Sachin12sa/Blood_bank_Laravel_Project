<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;

class AdminHospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function approve(Hospital $hospital)
    {
        $hospital->update(['status' => 'approved']);

        return redirect()->route('admin.hospitals.index')
                         ->with('success', "Hospital '{$hospital->hospital_name}' has been approved.");
    }

    public function reject(Hospital $hospital)
    {
        $hospital->update(['status' => 'rejected']);

        return redirect()->route('admin.hospitals.index')
                         ->with('success', "Hospital '{$hospital->hospital_name}' has been rejected.");
    }
}
