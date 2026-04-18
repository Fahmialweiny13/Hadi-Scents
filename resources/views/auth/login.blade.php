<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Hadi Scents</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
        }
        .login-card {
            max-width: 620px;
            border-radius: 20px;
            padding: 3.5rem
        }
        .brand {
            font-weight: 700;
            font-size: 36px; 
        }
        .brand span {
            color: #c08457;
        }
        .subtitle {
            font-size: 16px;
        }
        .btn-main {
            background-color: #c08457;
            border: none;
            font-size: 17px;
            padding: 14px;
        }
        .btn-main:hover {
            background-color: #a96e45;
        }
        .form-control {
            padding: 14px;
            font-size: 16px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card login-card shadow p-5">

        <!-- Logo & Judul -->
        <div class="text-center mb-4">
            <div class="brand">
                ✨ Hadi<span>Scents</span>
            </div>
            <div class="text-muted subtitle mt-2">
                Sistem Arus Kas<br>
                Kelola keuangan UMKM dengan mudah dan aman
            </div>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="alert alert-danger text-center small">
                Email atau password salah
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="email@example.com"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button class="btn btn-main text-white w-100 py-3">
                Masuk ke Dashboard
            </button>
        </form>

    </div>
</div>

</body>
</html>