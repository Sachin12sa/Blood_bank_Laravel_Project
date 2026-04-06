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

         public function googlelogin(){

        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication() {
        $user = Socialite::driver('google')->user();
        $isUser = User::where('email', $user->email)->first();

        if ($isUser) {
            Auth::login($isUser);

return redirect()->route('dashboard')->with('success', 'Login successfully');

        } else {
            // get the avatar URL from Google and save it to the profile field
            $avatarUrl = $user->getAvatar();
            // Improve image quality
             $avatarUrl = str_replace('s96-c', 's200-c', $avatarUrl);

            // Download image
            $response = Http::get($avatarUrl);

            if($response->successful()) {   
                $filename = date('YmdHis') . "_".Str::uuid() . '.jpg';
                Storage::disk('public')->put('profile/' . $filename, $response->body());
            } 

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => Hash::make(Str::random(10)),
                'profile' => $filename ?? null,
                'role' => 'User',
            ]);

            // Send email verification notification
            $newUser->sendEmailVerificationNotification();
            
            Auth::login($newUser);
            return redirect()->route('dashboard')->with('success', 'Login successfully');
        }
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
        return redirect()->intended('/dashboard')
                         ->with('success', 'Logged in successfully');
    }

    return redirect()->route('login')
                     ->with('error', 'Invalid email or password'); 
}
}
