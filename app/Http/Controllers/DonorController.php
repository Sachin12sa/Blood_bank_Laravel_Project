<?php

namespace App\Http\Controllers;

class DonorController extends Controller
{
    public function dashboard()
    {
        // null for now — donor profile comes after auth
        return view('donor.dashboard', ['donor' => null]);
    }
}