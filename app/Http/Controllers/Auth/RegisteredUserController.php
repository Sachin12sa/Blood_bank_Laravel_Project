<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:donor,hospital'],
        ]);

        // Role-specific validation
        if ($request->role === 'donor') {
            $request->validate([
                'blood_group'    => ['required', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
                'phone'          => ['nullable', 'string', 'max:20'],
                'date_of_birth'  => ['nullable', 'date', 'before:today'],
                'address'        => ['nullable', 'string', 'max:500'],
            ]);
        } else {
            $request->validate([
                'hospital_name'  => ['required', 'string', 'max:255'],
                'license_number' => ['required', 'string', 'max:100', 'unique:hospitals,license_number'],
                'phone'          => ['nullable', 'string', 'max:20'],
                'address'        => ['nullable', 'string', 'max:500'],
            ]);
        }

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role (safe)
            $user->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role, 'guard_name' => 'web'])->id);

            // Create role-specific profile
            if ($request->role === 'donor') {
                Donor::create([
                    'user_id'       => $user->id,
                    'blood_group'   => $request->blood_group,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'address'       => $request->address,
                    'is_eligible'   => true,
                ]);
            } else {
                Hospital::create([
                    'user_id'        => $user->id,
                    'hospital_name'  => $request->hospital_name,
                    'license_number' => $request->license_number,
                    'phone'          => $request->phone,
                    'address'        => $request->address,
                    'status'         => 'pending',
                ]);
            }

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);

        // Redirect based on role
        if ($request->role === 'donor') {
            return redirect()->route('donor.dashboard');
        }

        return redirect()->route('hospital.dashboard');
    }
}
