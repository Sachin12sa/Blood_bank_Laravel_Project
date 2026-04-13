<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;

class AdminDonorController extends Controller
{
    public function index()
    {
        $donors = Donor::with(['user', 'donations'])
            ->latest()
            ->paginate(15);

        return view('admin.donors.index', compact('donors'));
    }

    public function show(Donor $donor)
    {
        $donor->load(['user', 'donations.bloodUnit']);

        return view('admin.donors.show', compact('donor'));
    }
}
