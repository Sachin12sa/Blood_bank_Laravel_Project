<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Blood Bank') }} - Reset Password</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .blood-drop {
            background: radial-gradient(circle at 30% 30%, #dc2626 0%, transparent 50%), 
                        radial-gradient(circle at 70% 70%, #b91c1c 0%, transparent 50%);
        }
    </style>
</head>
<body class="font-['Figtree'] antialiased bg-gradient-to-br from-red-50 via-pink-50/30 to-rose-50 min-h-screen">
    <div class="flex flex-col justify-center min-h-screen py-12 sm:px-6 lg:px-8">
        <!-- Background blood drops decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 blood-drop opacity-10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 blood-drop opacity-5 rounded-full blur-2xl"></div>
        </div>

        <div class="relative mx-auto max-w-md w-full space-y-8">
            <!-- Header (no logo) -->
            <div class="flex flex-col items-center">
                <h2 class="mt-8 text-3xl font-bold text-gray-900 tracking-tight">Blood Bank</h2>
                <p class="mt-2 text-lg text-red-700 font-medium text-center max-w-sm">
                    Saving lives, one donation at a time
                </p>
            </div>

            <!-- Hero Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-2xl p-3 mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.27 7.27c.883.883 2.317.883 3.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">Reset Your Password</h1>
                <p class="text-lg text-gray-600 max-w-sm mx-auto leading-relaxed">
                    Enter your email address and we'll send you a secure link to reset your password instantly.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl border border-red-200/50 p-8 space-y-6">
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input Group -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-gray-900 leading-6">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                class="block w-full pl-12 pr-4 py-4 border-2 border-red-200 rounded-xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 transition-all duration-300 shadow-sm bg-white/80 backdrop-blur-sm text-lg placeholder-gray-500 @error('email') border-red-500 ring-2 ring-red-500/20 bg-red-50/30 @enderror" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                placeholder="Enter your registered email"
                            />
                            @error('email')
                                <p class="mt-1 text-red-600 font-medium bg-red-50/50 px-3 py-2 rounded-lg border border-red-200 text-sm">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 px-8 text-lg font-bold shadow-lg transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] inline-flex items-center justify-center bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/50 active:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.27 7.27c.883.883 2.317.883 3.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ __('Send Reset Link') }}</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info & Footer -->
        <div class="text-center space-y-4">
            <p class="text-sm text-gray-600">Didn't receive the email? Check your spam folder.</p>
            <div class="text-sm text-gray-500 space-y-2">
                <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium transition-colors inline-flex items-center space-x-1">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>

