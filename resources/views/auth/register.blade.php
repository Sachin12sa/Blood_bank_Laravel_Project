<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Blood Bank</title>

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

    .bg-drops {
      position: fixed; inset: 0; pointer-events: none; z-index: 0;
    }
    .drop {
      position: absolute;
      width: 16px; height: 22px;
      background: rgba(192,21,42,0.4);
      border-radius: 50%;
      animation: fall 10s linear infinite;
    }
    @keyframes fall {
      0% { transform: translateY(-50px); opacity: 0; }
      50% { opacity: 1; }
      100% { transform: translateY(100vh); opacity: 0; }
    }

    .card {
      display: flex;
      width: 900px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    }

    .left-panel {
      flex: 1;
      background: linear-gradient(160deg, rgba(139,0,0,0.6), rgba(0,0,0,0.9)),
      url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=800') center/cover;
      color: white;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
    }

    .left-panel h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
    }

    .right-panel {
      width: 400px;
      background: var(--cream);
      padding: 40px;
    }

    .form-heading {
      font-size: 1.8rem;
      margin-bottom: 5px;
    }

    .form-sub {
      font-size: 0.85rem;
      color: gray;
      margin-bottom: 20px;
    }

    .field {
      margin-bottom: 15px;
    }

    .field input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      background: crimson;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .form-footer {
      text-align: center;
      margin-top: 15px;
      font-size: 0.85rem;
    }

    .alert-error {
      background: #ffe5e5;
      padding: 10px;
      margin-bottom: 10px;
      border-left: 4px solid red;
    }

  </style>
</head>

<body>

<div class="card">

  <!-- Left -->
  <div class="left-panel">
    <h2>Join Us.<br>Donate Blood.</h2>
    <p>Be a hero. Save lives today.</p>
  </div>

  <!-- Right -->
  <div class="right-panel">

    <div class="form-heading">Create Account</div>
    <p class="form-sub">Sign up to get started</p>

    <!-- Errors -->
    @if ($errors->any())
      <div class="alert-error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Form -->
    <form action="{{ route('register') }}" method="POST">
      @csrf

      <div class="field">
        <input type="text" name="name" placeholder="Full Name" required>
      </div>

      <div class="field">
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <div class="field">
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <div class="field">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
      </div>

      <button type="submit" class="btn-submit">Register</button>
    </form>

    <p class="form-footer">
      Already have an account?
      <a href="{{ route('login') }}">Login</a>
    </p>

  </div>

</div>

</body>
</html>