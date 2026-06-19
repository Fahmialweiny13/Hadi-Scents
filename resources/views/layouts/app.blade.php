<!DOCTYPE html>
<html lang="id">
<head>
    <style>
        :root {
            --gold: #c9a227;
            --gold-soft: #d1bf73;
        }
    
        .text-gold {
            color: var(--gold) !important;
        }

        .bg-gold-soft {
            background-color: rgba(200, 162, 37, 0.05)

        }

        .nav-link:hover {
            background-color: rgba(166, 137, 41, 0.05);
            color: #69530c;

        }
    
        .bg-gold {
            background-color: var(--gold) !important;
            color: #fff !important;
        }
    
        .btn-gold {
            background-color: var(--gold);
            color: #fff;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15)
        }
    
        .btn-gold:hover {
            background-color: #b8961f;
            color: #fff;
        }
        .btn-gold-outline {
            border: 1px solid var(--gold) !important;
            color: #212529 !important;
            background-color: transparent !important;
        }

        .btn-gold-outline:hover,
        .btn-gold-outline:focus {
            background-color: rgba(201, 162, 39, 0.15) !important;
            color: #212529 !important;
            border-color: var(--gold) !important;
        }
        .nav-link.active {
             border-left: 3px solid var(--gold);
             padding-left: 0.75rem;
        }

        .card {
            transition: all .2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .25rem .75rem rgba(0,0,0,.08);
        }

        .logo-wrap {
            width: 60px;
            height: 60px;
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,.12);
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        ::placeholder {
            font-size: 0.75rem;
            color: #9ca3af
        }
        textarea::placeholder {
            font-size: 0.75rem;
        }
        
        .table-fixed {
            table-layout: fixed;
        }
       
        
    </style>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hadi Scents')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

       {{-- SIDEBAR --}}
<nav class="col-md-2 d-none d-md-block bg-white sidebar shadow-sm vh-100 p-3 flex column">
    <div class="d-flex align-items-center gap-3 mb-4 bottom-line">
        <div class="logo-wrap">
            <img src="{{ asset('img/logo-hadi.png') }}" alt="Hadi Scents">
        </div>
        <div>
            <h4 class="mb-0 fw-bold">Hadi <span class="text-gold">Scents</span></h4>
            <small class="text-muted">Cashflow Admin</small>
        </div>
    </div>

    <ul class="nav flex-column gap-3 flex-grow-1">

        <li class="nav-item mt-1">
            <a class="nav-link d-flex align-items-center gap-2 
                {{ request()->routeIs('dashboard') ? 'active fw-semibold text-gold' : 'text-dark' }}"
                href="{{ route('dashboard')}}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 
                {{ request()->is('kas-masuk') ? 'active fw-semibold text-gold bg-gold-soft' : 'text-dark' }}"
                href="/kas-masuk">
                <i class="bi bi-arrow-down-circle"></i>
                Kas Masuk
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 
                {{ request()->is('kas-keluar') ? 'active fw-semibold text-gold' : 'text-dark' }}"
                href="/kas-keluar">
                <i class="bi bi-arrow-up-circle"></i>
                Kas Keluar
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 
                {{ request()->is('laporan') ? 'active fw-semibold text-gold' : 'text-dark' }}"
                href="/laporan">
                <i class="bi bi-file-earmark-text"></i>
                Laporan
            </a>
        </li>

    <li class="nav-item mt-5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </button>
            </form>
    </li>
</ul>
</nav>

        {{-- CONTENT --}}
        <main class="col-md-10 px-4 py-4 bg-gold-soft min-vh-100">
            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('hide.bs.modal', function (event) {
        const activeElement = document.activeElement;

        if (activeElement instanceof HTMLElement && event.target instanceof HTMLElement && event.target.contains(activeElement)) {
            activeElement.blur();
        }
    });
</script>
</body>
</html>