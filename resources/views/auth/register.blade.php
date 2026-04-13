<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Blood Bank</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --crimson: #C0152A;
      --crimson-dark: #8B0000;
      --crimson-light: #E8334A;
      --blood-deep: #1a0005;
      --cream: #FFF8F8;
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
      padding: 20px;
    }
    .card {
      display: flex; width: 960px; max-width: 100%;
      border-radius: 20px; overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    }
    .left-panel {
      flex: 1;
      background: linear-gradient(160deg, rgba(139,0,0,0.6), rgba(0,0,0,0.9)),
        url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=800') center/cover;
      color: white; padding: 40px;
      display: flex; flex-direction: column; justify-content: flex-end;
      min-height: 500px;
    }
    .left-panel h2 { font-family: 'Playfair Display', serif; font-size: 2rem; }
    .left-panel p { margin-top: 8px; opacity: 0.85; font-size: 0.95rem; }
    .right-panel {
      width: 480px; background: var(--cream); padding: 36px 40px;
      overflow-y: auto; max-height: 90vh;
    }
    .form-heading { font-size: 1.6rem; font-weight: 700; margin-bottom: 4px; }
    .form-sub { font-size: 0.85rem; color: gray; margin-bottom: 20px; }

    /* Role Tabs */
    .role-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
    .role-tab {
      flex: 1; padding: 12px; border: 2px solid #e0e0e0; border-radius: 12px;
      background: #fff; cursor: pointer; text-align: center; transition: all 0.2s;
      font-weight: 500; font-size: 0.9rem;
    }
    .role-tab:hover { border-color: var(--crimson-light); }
    .role-tab.active { border-color: var(--crimson); background: #fff0f0; color: var(--crimson); font-weight: 600; }
    .role-tab i { display: block; font-size: 1.4rem; margin-bottom: 4px; }

    .field { margin-bottom: 14px; }
    .field label { display: block; font-size: 0.82rem; font-weight: 500; margin-bottom: 4px; color: #555; }
    .field input, .field select {
      width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #ddd;
      font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
      transition: border-color 0.2s; outline: none;
    }
    .field input:focus, .field select:focus { border-color: var(--crimson); }
    .field-row { display: flex; gap: 12px; }
    .field-row .field { flex: 1; }

    .role-fields { display: none; }
    .role-fields.active { display: block; }

    .btn-submit {
      width: 100%; padding: 12px; background: var(--crimson); color: white;
      border: none; border-radius: 10px; cursor: pointer; font-weight: 600;
      font-size: 0.95rem; transition: background 0.2s; margin-top: 8px;
    }
    .btn-submit:hover { background: var(--crimson-dark); }
    .form-footer { text-align: center; margin-top: 16px; font-size: 0.85rem; }
    .form-footer a { color: var(--crimson); text-decoration: none; font-weight: 500; }

    .alert-error {
      background: #ffe5e5; padding: 10px 14px; margin-bottom: 14px;
      border-left: 4px solid red; border-radius: 6px; font-size: 0.85rem;
    }
    .alert-error ul { margin: 0; padding-left: 18px; }

    @media (max-width: 768px) {
      .card { flex-direction: column; }
      .left-panel { min-height: 200px; padding: 30px; }
      .right-panel { width: 100%; }
    }
  </style>
</head>
<body>

<div class="card">
  <div class="left-panel">
    <h2>Join Us.<br>Save Lives.</h2>
    <p>Register as a donor or hospital to be part of the blood bank network.</p>
  </div>

  <div class="right-panel">
    <div class="form-heading">Create Account</div>
    <p class="form-sub">Sign up to get started</p>

    @if ($errors->any())
      <div class="alert-error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register') }}" method="POST" id="registerForm">
      @csrf

      {{-- Role Selection --}}
      <div class="role-tabs">
        <div class="role-tab active" data-role="donor" onclick="selectRole('donor')">
          <i class="bi bi-heart-pulse-fill"></i> Donor
        </div>
        <div class="role-tab" data-role="hospital" onclick="selectRole('hospital')">
          <i class="bi bi-hospital-fill"></i> Hospital
        </div>
      </div>
      <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'donor') }}">

      {{-- Common Fields --}}
      <div class="field">
        <label>Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
      </div>
      <div class="field">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required>
      </div>
      <div class="field-row">
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Min 8 characters" required>
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" placeholder="Re-enter password" required>
        </div>
      </div>

      {{-- Donor Fields --}}
      <div class="role-fields active" id="donorFields">
        <div class="field-row">
          <div class="field">
            <label>Blood Group</label>
            <select name="blood_group">
              <option value="">Select Blood Group</option>
              @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
          </div>
        </div>
        <div class="field">
          <label>Phone Number</label>
          <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+977-9800000000">
        </div>
        <div class="field">
          <label>Address</label>
          <input type="text" name="address" value="{{ old('address') }}" placeholder="Your city or area">
        </div>
      </div>

      {{-- Hospital Fields --}}
      <div class="role-fields" id="hospitalFields">
        <div class="field">
          <label>Hospital Name</label>
          <input type="text" name="hospital_name" value="{{ old('hospital_name') }}" placeholder="Hospital name">
        </div>
        <div class="field">
          <label>License Number</label>
          <input type="text" name="license_number" value="{{ old('license_number') }}" placeholder="Medical license number">
        </div>
        <div class="field">
          <label>Phone Number</label>
          <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+977-01-1234567">
        </div>
        <div class="field">
          <label>Address</label>
          <input type="text" name="address" value="{{ old('address') }}" placeholder="Hospital address">
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <span id="btnText">Register as Donor</span>
      </button>
    </form>

    <p class="form-footer">
      Already have an account?
      <a href="{{ route('login') }}">Login</a>
    </p>
  </div>
</div>

<script>
  function selectRole(role) {
    document.getElementById('roleInput').value = role;

    // Toggle tab styling
    document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`.role-tab[data-role="${role}"]`).classList.add('active');

    // Toggle field sections
    document.getElementById('donorFields').classList.toggle('active', role === 'donor');
    document.getElementById('hospitalFields').classList.toggle('active', role === 'hospital');

    // Toggle required attributes
    const donorInputs = document.querySelectorAll('#donorFields input, #donorFields select');
    const hospitalInputs = document.querySelectorAll('#hospitalFields input');

    if (role === 'donor') {
      document.querySelector('#donorFields select[name="blood_group"]').setAttribute('required', '');
      hospitalInputs.forEach(i => i.removeAttribute('required'));
    } else {
      document.querySelector('#donorFields select[name="blood_group"]').removeAttribute('required');
      document.querySelector('#hospitalFields input[name="hospital_name"]').setAttribute('required', '');
      document.querySelector('#hospitalFields input[name="license_number"]').setAttribute('required', '');
    }

    // Update button text
    document.getElementById('btnText').textContent = role === 'donor' ? 'Register as Donor' : 'Register as Hospital';
  }

  // Restore role from old input
  document.addEventListener('DOMContentLoaded', () => {
    const role = document.getElementById('roleInput').value;
    if (role) selectRole(role);
  });
</script>

</body>
</html>