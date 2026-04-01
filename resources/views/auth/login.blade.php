<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Blood Bank</title>
  @vite('resources/css/app.css')
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --crimson: #C0152A;
      --crimson-dark: #8B0000;
      --crimson-light: #E8334A;
      --blood-deep: #1a0005;
      --cream: #FFF8F8;
      --muted: #9CA3AF;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--blood-deep);
      background-image:
        radial-gradient(ellipse 80% 60% at 10% 50%, rgba(139,0,0,0.35) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 90% 50%, rgba(192,21,42,0.18) 0%, transparent 60%);
      overflow: hidden;
    }

    /* Animated background drops */
    .bg-drops {
      position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
    }
    .drop {
      position: absolute;
      border-radius: 50% 50% 45% 55% / 60% 40% 60% 40%;
      background: radial-gradient(circle at 40% 35%, rgba(192,21,42,0.55), rgba(139,0,0,0.18));
      filter: blur(2px);
      animation: drift linear infinite;
    }
    .drop:nth-child(1) { width:18px; height:24px; left:12%; animation-duration:14s; animation-delay:0s; }
    .drop:nth-child(2) { width:12px; height:16px; left:28%; animation-duration:18s; animation-delay:4s; }
    .drop:nth-child(3) { width:22px; height:30px; left:62%; animation-duration:12s; animation-delay:2s; }
    .drop:nth-child(4) { width:10px; height:14px; left:80%; animation-duration:20s; animation-delay:7s; }
    .drop:nth-child(5) { width:16px; height:22px; left:45%; animation-duration:16s; animation-delay:1s; }
    @keyframes drift {
      0%   { transform: translateY(-40px) rotate(0deg); opacity:0; }
      10%  { opacity: 0.7; }
      90%  { opacity: 0.4; }
      100% { transform: translateY(110vh) rotate(20deg); opacity:0; }
    }

    /* Card */
    .card {
      position: relative; z-index: 10;
      display: flex;
      width: min(900px, 96vw);
      min-height: 560px;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 40px 100px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.06);
      animation: cardIn 0.7s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes cardIn {
      from { opacity:0; transform: translateY(40px) scale(0.97); }
      to   { opacity:1; transform: translateY(0) scale(1); }
    }

    /* Left panel */
    .left-panel {
      flex: 1;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 40px;
      overflow: hidden;
      min-width: 0;
    }
    .left-bg {
      position: absolute; inset: 0;
      background:
        linear-gradient(160deg, rgba(139,0,0,0.5) 0%, rgba(26,0,5,0.85) 100%),
        url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=800&q=80') center/cover no-repeat;
    }
    /* heartbeat line */
    .heartbeat-wrap {
      position: absolute; top: 32px; left: 0; right: 0; height: 60px;
      display: flex; align-items: center; justify-content: center;
    }
    .heartbeat-svg { width: 80%; max-width: 260px; }
    .hb-line {
      stroke-dasharray: 400;
      stroke-dashoffset: 400;
      animation: draw 2.5s ease forwards 0.5s, pulse 2s ease-in-out infinite 3s;
    }
    @keyframes draw { to { stroke-dashoffset: 0; } }
    @keyframes pulse {
      0%,100% { opacity:1; } 50% { opacity:0.4; }
    }

    .left-content { position: relative; z-index: 2; }
    .blood-drop-icon {
      width: 52px; height: 52px;
      background: linear-gradient(135deg, var(--crimson-light), var(--crimson-dark));
      border-radius: 50% 50% 45% 55% / 60% 40% 60% 40%;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 20px;
      box-shadow: 0 0 30px rgba(192,21,42,0.6);
      animation: glow 3s ease-in-out infinite;
    }
    @keyframes glow {
      0%,100% { box-shadow: 0 0 20px rgba(192,21,42,0.5); }
      50%      { box-shadow: 0 0 45px rgba(192,21,42,0.9); }
    }
    .left-content h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 900;
      color: #fff;
      line-height: 1.15;
      margin-bottom: 16px;
    }
    .left-content h2 span { color: var(--crimson-light); }
    .left-content p {
      font-size: 0.92rem;
      color: rgba(255,255,255,0.65);
      max-width: 280px;
      line-height: 1.7;
    }
    .stat-pills {
      display: flex; gap: 12px; margin-top: 28px; flex-wrap: wrap;
    }
    .pill {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 100px;
      padding: 6px 14px;
      font-size: 0.78rem;
      color: rgba(255,255,255,0.8);
      backdrop-filter: blur(4px);
    }
    .pill strong { color: #fff; }

    /* Right panel */
    .right-panel {
      width: 400px;
      flex-shrink: 0;
      background: var(--cream);
      padding: 44px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      overflow-y: auto;
    }

    .logo-row {
      display: flex; align-items: center; gap: 10px; margin-bottom: 32px;
    }
    .logo-dot {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--crimson), var(--crimson-dark));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .logo-dot svg { width: 18px; height: 18px; fill: #fff; }
    .logo-text { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.1rem; color: #1a0005; }
    .logo-text span { color: var(--crimson); }

    .form-heading {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      font-weight: 700;
      color: #1a0005;
      margin-bottom: 4px;
    }
    .form-sub {
      font-size: 0.85rem;
      color: var(--muted);
      margin-bottom: 28px;
    }

    /* Alerts */
    .alert-error {
      background: #fff0f0;
      border-left: 3px solid var(--crimson);
      color: #8B0000;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.82rem;
      margin-bottom: 18px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 8px;
    }
    .alert-error ul { list-style: disc; padding-left: 16px; }
    .alert-error button { background: none; border: none; cursor: pointer; color: inherit; flex-shrink: 0; margin-top: 1px; }

    /* Inputs */
    .field { margin-bottom: 18px; }
    .field label {
      display: block;
      font-size: 0.82rem;
      font-weight: 500;
      color: #3a0010;
      margin-bottom: 6px;
    }
    .input-wrap { position: relative; }
    .input-icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: var(--muted);
      width: 16px; height: 16px;
    }
    .field input {
      width: 100%;
      padding: 10px 14px 10px 40px;
      border: 1.5px solid #e5cdd0;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      color: #1a0005;
      background: #fff;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field input:focus {
      border-color: var(--crimson);
      box-shadow: 0 0 0 3px rgba(192,21,42,0.12);
    }
    .field input::placeholder { color: #c9a8ad; }

    /* Extras row */
    .extras-row {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 22px; font-size: 0.82rem;
    }
    .remember-label { display: flex; align-items: center; gap: 6px; color: #5a2030; cursor: pointer; }
    .remember-label input[type="checkbox"] {
      accent-color: var(--crimson);
      width: 14px; height: 14px;
    }
    .forgot-link { color: var(--crimson); text-decoration: none; font-weight: 500; }
    .forgot-link:hover { text-decoration: underline; }

    /* Submit */
    .btn-submit {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, var(--crimson), var(--crimson-dark));
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      letter-spacing: 0.3px;
      position: relative;
      overflow: hidden;
      transition: transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(192,21,42,0.4);
    }
    .btn-submit::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
      opacity: 0; transition: opacity 0.2s;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(192,21,42,0.5); }
    .btn-submit:hover::after { opacity: 1; }
    .btn-submit:active { transform: translateY(0); }

    /* Divider */
    .divider {
      display: flex; align-items: center; gap: 10px; margin: 20px 0;
    }
    .divider hr { flex: 1; border: none; border-top: 1px solid #ebd5d8; }
    .divider span { font-size: 0.78rem; color: var(--muted); font-weight: 500; }

    /* Google btn */
    .btn-google {
      width: 100%;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      padding: 10px;
      border: 1.5px solid #e5cdd0;
      border-radius: 10px;
      background: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.88rem;
      font-weight: 500;
      color: #3a0010;
      text-decoration: none;
      transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
    }
    .btn-google:hover {
      background: #fff0f0;
      border-color: var(--crimson);
      box-shadow: 0 2px 12px rgba(192,21,42,0.1);
    }

    /* Footer */
    .form-footer {
      text-align: center; margin-top: 20px;
      font-size: 0.82rem; color: var(--muted);
    }
    .form-footer a { color: var(--crimson); text-decoration: none; font-weight: 500; }
    .form-footer a:hover { text-decoration: underline; }

    /* Responsive */
    @media (max-width: 680px) {
      .left-panel { display: none; }
      .right-panel { width: 100%; border-radius: 24px; }
    }
  </style>
</head>
<body>

  <!-- Animated blood drops -->
  <div class="bg-drops">
    <div class="drop"></div>
    <div class="drop"></div>
    <div class="drop"></div>
    <div class="drop"></div>
    <div class="drop"></div>
  </div>

  <div class="card">

    <!-- Left Panel -->
    <div class="left-panel">
      <div class="left-bg"></div>

      <!-- ECG heartbeat line -->
      <div class="heartbeat-wrap">
        <svg class="heartbeat-svg" viewBox="0 0 260 60" fill="none" xmlns="http://www.w3.org/2000/svg">
          <polyline class="hb-line"
            points="0,30 40,30 55,30 65,8 75,52 85,20 95,42 105,30 260,30"
            stroke="rgba(255,255,255,0.7)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="left-content">
        <div class="blood-drop-icon">
          <!-- Blood drop SVG -->
          <svg viewBox="0 0 24 24" fill="white" width="22" height="22">
            <path d="M12 2C12 2 5 10.5 5 15a7 7 0 0014 0C19 10.5 12 2 12 2z"/>
          </svg>
        </div>
        <h2>Save Lives.<br><span>Donate Blood.</span></h2>
        <p>Every drop counts. Your donation can give someone a second chance at life.</p>
        <div class="stat-pills">
          <div class="pill"><strong>4.5M</strong> lives saved/yr</div>
          <div class="pill"><strong>Every 2s</strong> blood needed</div>
          <div class="pill"><strong>All types</strong> accepted</div>
        </div>
      </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
      <!-- Logo -->
      <div class="logo-row">
        <div class="logo-dot">
          <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 10.5 5 15a7 7 0 0014 0C19 10.5 12 2 12 2z"/></svg>
        </div>
        <div class="logo-text">Blood<span>Bank</span></div>
      </div>

      <div class="form-heading">Welcome back</div>
      <p class="form-sub">Sign in to your account to continue</p>

      <!-- Error Alerts -->
      @if ($errors->any())
        <div class="alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" onclick="this.parentElement.style.display='none'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert-error">
          <span>{{ session('error') }}</span>
          <button type="button" onclick="this.parentElement.style.display='none'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      @endif

      <!-- Form -->
      <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div class="field">
          <label>Email address</label>
          <div class="input-wrap">
            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
            <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
          </div>
        </div>

        <div class="field">
          <label>Password</label>
          <div class="input-wrap">
            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <input type="password" name="password" placeholder="••••••••" required>
          </div>
        </div>

        <div class="extras-row">
          <label class="remember-label">
            <input type="checkbox" name="remember"> Remember me
          </label>
          <a href="forgot-password" class="forgot-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
      </form>

      <div class="divider">
        <hr><span>OR</span><hr>
      </div>

      <a href="{{ route('auth.google') }}" class="btn-google">
        <img src="https://img.icons8.com/color/24/google-logo.png" alt="Google" width="20" height="20">
        Sign in with Google
      </a>

      <p class="form-footer">
        Don't have an account? <a href="{{ route('signup') }}">Sign up</a>
      </p>
    </div>

  </div>

</body>
</html>