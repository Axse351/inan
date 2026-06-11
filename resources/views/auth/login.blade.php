<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Spare Parts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a252f 0%, #2c3e50 60%, #34495e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .4);
        }

        .login-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 .2rem rgba(52, 152, 219, .2);
        }

        .btn-login {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: .65rem;
            font-weight: 600;
            letter-spacing: .03em;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9, #1a6fa0);
        }

        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-left: none;
        }
    </style>
</head>

<body>
    <div class="container px-3">
        <div class="card login-card mx-auto">
            <div class="card-body p-4 p-sm-5">

                {{-- Logo --}}
                <div class="login-logo">
                    <i class="bi bi-tools text-white fs-3"></i>
                </div>
                <h4 class="text-center fw-bold mb-1">Stok Gudang</h4>
                <p class="text-center text-muted small mb-4">Sistem Manajemen Gudang</p>

                {{-- Error global --}}
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Session error (misal dari middleware) --}}
                @if (session('error'))
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="email@example.com" value="{{ old('email') }}" autofocus required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                required>
                            <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-password"
                                tabindex="-1">
                                <i class="bi bi-eye" id="eye-icon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

            </div>
        </div>

        <p class="text-center mt-4 text-white-50 small">
            &copy; {{ date('Y') }} Spare Parts Workshop
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    </script>
</body>

</html>
