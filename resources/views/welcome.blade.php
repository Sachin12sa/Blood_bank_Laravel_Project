<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodBank - Save a Life Today</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --red: #e02020;
            --red-dark: #b91414;
            --red-soft: #fff0f0;
            --gray-50: #f8f9fb;
            --gray-100: #f1f3f7;
            --gray-700: #3a3f52;
            --gray-900: #171b2d;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font);
            background: #fff;
            color: var(--gray-900);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #fff !important;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--red);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 15px rgba(224, 32, 32, 0.4);
        }

        /* Hero */
        .hero {
            position: relative;
            padding: 100px 0;
            background: linear-gradient(135deg, var(--red-soft) 0%, #fff 100%);
            overflow: hidden;
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.1;
            margin-bottom: 24px;
        }
        .hero-title span {
            color: var(--red);
        }
        .hero-text {
            font-size: 1.25rem;
            color: var(--gray-700);
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .btn-custom {
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        .btn-primary-custom {
            background: var(--red);
            color: #fff;
            box-shadow: 0 10px 25px rgba(224, 32, 32, 0.3);
            border: none;
        }
        .btn-primary-custom:hover {
            background: var(--red-dark);
            transform: translateY(-3px);
            color: #fff;
        }

        /* Stats */
        .stats-section {
            padding: 60px 0;
            background: #fff;
        }
        .stat-box {
            text-align: center;
            padding: 30px;
            border-radius: 24px;
            background: var(--gray-50);
            border: 1px solid var(--gray-100);
            transition: transform 0.3s;
        }
        .stat-box:hover {
            transform: translateY(-10px);
            border-color: var(--red-soft);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--red);
            margin-bottom: 10px;
            line-height: 1;
        }
        .stat-label {
            font-size: 1.1rem;
            color: var(--gray-700);
            font-weight: 600;
        }

        /* Search Section */
        .search-section {
            padding: 80px 0;
            background: var(--gray-900);
            color: #fff;
        }
        .search-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 30px;
            backdrop-filter: blur(10px);
        }
        .search-input-group {
            background: #fff;
            padding: 10px;
            border-radius: 20px;
            display: flex;
            gap: 10px;
        }
        .search-select {
            border: none;
            outline: none;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            padding-left: 15px;
        }
        .search-select:focus {
            box-shadow: none;
            border: none;
        }
        .search-btn {
            border-radius: 12px;
            padding: 12px 30px;
        }

        /* Campaigns */
        .campaign-card {
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
        }
        .campaign-card:hover {
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            transform: translateY(-5px);
        }
        .camp-date {
            background: var(--red-soft);
            color: var(--red);
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        /* Footer */
        footer {
            background: var(--gray-50);
            padding: 40px 0;
            border-top: 1px solid var(--gray-200);
        }
        
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="brand-icon">
                    <i class="bi bi-droplet-fill"></i>
                </div>
                Blood<span style="color: var(--red)">Bank</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link fw-bold" href="#search">Search Blood</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="#campaigns">Camps</a></li>
                </ul>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-custom btn-primary-custom">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn fw-bold text-dark" style="margin-top: 8px;">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom">Register Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Your Blood Can Bring <span>Smile</span> In Others Face.</h1>
                    <p class="hero-text">A single drop of blood can make a huge difference. Join our network of heroes, hospitals, and coordinators to save lives faster.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom">Become a Donor <i class="bi bi-arrow-right ms-2"></i></a>
                        @endguest
                        <a href="#search" class="btn btn-custom bg-white border fw-bold text-dark shadow-sm">Find Blood Unit</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                    <img src="https://ui-avatars.com/api/?background=ff0000&color=fff&name=Blood+Donation&size=400&font-size=0.15&rounded=true&bold=true" class="img-fluid rounded-circle shadow-lg" alt="Blood Donation" style="max-width: 400px; padding: 10px; background: white;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['donors'] ?? 0 }}</div>
                        <div class="stat-label">Registered Donors</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['blood_units'] ?? 0 }}</div>
                        <div class="stat-label">Available Units</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['hospitals'] ?? 0 }}</div>
                        <div class="stat-label">Verified Hospitals</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['requests_fulfilled'] ?? 0 }}</div>
                        <div class="stat-label">Requests Fulfilled</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blood Search Ajax Section -->
    <section class="search-section" id="search">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-3">Live Blood Availability</h2>
                    <p class="mb-5 lead text-white-50">Search our inventory to check real-time stock levels of specific blood groups before proceeding to request.</p>
                    
                    <div class="search-box">
                        <div class="search-input-group">
                            <select class="form-select search-select" id="searchBloodGroup">
                                <option value="" selected disabled>Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                            <button onclick="searchBlood()" class="btn btn-primary-custom btn-custom search-btn w-100" style="max-width: 200px;">
                                <i class="bi bi-search me-2"></i> Search
                            </button>
                        </div>
                        
                        <div id="search-result-container" class="mt-4 pt-4 border-top border-secondary d-none">
                            <h3 class="fw-bold mb-1"><span id="search-result-units" class="text-danger display-4 fw-black">0</span> Units Available</h3>
                            <p class="text-white-50 mb-0">of <span id="search-result-group" class="fw-bold text-white"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Campaigns -->
    <section class="stats-section" id="campaigns">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Upcoming Donation Camps</h2>
                <p class="text-secondary lead">Join us at our next local events and make a difference.</p>
            </div>
            <div class="row g-4">
                @forelse($campaigns ?? [] as $camp)
                    <div class="col-lg-4 col-md-6">
                        <div class="campaign-card p-4 bg-white">
                            <span class="camp-date"><i class="bi bi-calendar-event me-2"></i>{{ $camp->date->format('M d, Y') }}</span>
                            <h4 class="fw-bold mb-3">{{ $camp->title }}</h4>
                            <p class="text-secondary mb-4">{{ Str::limit($camp->description, 100) }}</p>
                            <div class="d-flex align-items-center text-dark fw-medium">
                                <div class="brand-icon me-3" style="width: 35px; height: 35px; border-radius: 50%; opacity: 0.8;">
                                    <i class="bi bi-geo-alt-fill fs-6"></i>
                                </div>
                                {{ $camp->address }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-secondary py-5">
                        <div class="fs-1 mb-3"><i class="bi bi-calendar-x"></i></div>
                        <h5>No upcoming camps scheduled at the moment.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <div class="fw-bold d-flex align-items-center justify-content-center gap-2 mb-3 fs-5">
                <div class="brand-icon" style="width: 30px; height: 30px; border-radius: 8px;">
                    <i class="bi bi-droplet-fill fs-6"></i>
                </div>
                BloodBank
            </div>
            <p class="text-secondary">© {{ date('Y') }} BloodBank Management System. Built with Laravel.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchBlood() {
            const group = document.getElementById('searchBloodGroup').value;
            if(!group) {
                alert('Please select a blood group to search.');
                return;
            }

            fetch('{{ route("search.blood") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ blood_group: group })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('search-result-container').classList.remove('d-none');
                document.getElementById('search-result-units').innerText = data.available_units;
                document.getElementById('search-result-group').innerText = data.blood_group;
                
                if(data.available_units > 0) {
                    document.getElementById('search-result-units').className = 'text-success display-4 fw-bold';
                } else {
                    document.getElementById('search-result-units').className = 'text-danger display-4 fw-bold';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during search.');
            });
        }
    </script>
</body>
</html>
