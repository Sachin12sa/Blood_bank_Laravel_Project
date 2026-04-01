<?php

namespace App\Http\Controllers;

class HospitalController extends Controller
{
    public function dashboard()
    {
        // null for now — hospital profile comes after auth
        return view('hospital.dashboard', ['hospital' => null]);
    }
}