<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function googlelogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication()
    {
        $user = Socialite::driver('google')->user();
        $isUser = User::where('email', $user->email)->first();

        if ($isUser) {
            Auth::login($isUser);
            return $this->redirectByRole($isUser);
        } else {
            $avatarUrl = $user->getAvatar();
            $avatarUrl = str_replace('s96-c', 's200-c', $avatarUrl);

            $response = Http::get($avatarUrl);
            $filename = null;

            if ($response->successful()) {
                $filename = date('YmdHis') . "_" . Str::uuid() . '.jpg';
                Storage::disk('public')->put('profile/' . $filename, $response->body());
            }

            $newUser = User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => Hash::make(Str::random(10)),
            ]);

// Default Google users get donor role (safe assignment)
            $newUser->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'donor', 'guard_name' => 'web'])->id);

            // Create associated donor record
            \App\Models\Donor::firstOrCreate(
                ['user_id' => $newUser->id],
                ['is_eligible' => true]
            );

            $newUser->sendEmailVerificationNotification();
            Auth::login($newUser);

            return $this->redirectByRole($newUser);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return redirect()->route('login')
                         ->with('error', 'Invalid email or password');
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectByRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                             ->with('success', 'Logged in successfully');
        } elseif ($user->hasRole('hospital')) {
            return redirect()->route('hospital.dashboard')
                             ->with('success', 'Logged in successfully');
        } else {
            return redirect()->route('donor.dashboard')
                             ->with('success', 'Logged in successfully');
        }
    }
}
