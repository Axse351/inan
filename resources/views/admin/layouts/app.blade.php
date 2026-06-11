<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Spare Parts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: .5rem 1rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .1);
            border-radius: 6px;
        }

        .sidebar .nav-link i {
            width: 20px;
        }

        .sidebar-heading {
            color: #7f8c8d;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .5rem 1rem;
        }

        .main-content {
            min-height: 100vh;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <nav class="sidebar d-flex flex-column p-3" style="width:240px; min-width:240px;">
            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <i class="bi bi-tools fs-4 me-2"></i>
                <span class="fw-bold">Spare Parts</span>
            </a>
            <hr class="border-secondary">

            <div class="sidebar-heading">Menu</div>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @active('admin/dashboard')">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading mt-2">Master Data</div>
            <ul class="nav flex-column mb-2">
                <li><a href="{{ route('admin.barang.index') }}" class="nav-link @active('admin/barang*')"><i
                            class="bi bi-box-seam me-2"></i> Barang</a></li>
                <li><a href="{{ route('admin.kategori.index') }}" class="nav-link @active('admin/kategori*')"><i
                            class="bi bi-tags me-2"></i> Kategori</a></li>
                <li><a href="{{ route('admin.satuan.index') }}" class="nav-link @active('admin/satuan*')"><i
                            class="bi bi-rulers me-2"></i> Satuan</a></li>
                <li><a href="{{ route('admin.pemasok.index') }}" class="nav-link @active('admin/pemasok*')"><i
                            class="bi bi-truck me-2"></i> Pemasok</a></li>
            </ul>

            <div class="sidebar-heading mt-2">Transaksi</div>
            <ul class="nav flex-column mb-2">
                <li><a href="{{ route('admin.barang_masuk.index') }}" class="nav-link @active('admin/barang-masuk*')"><i
                            class="bi bi-box-arrow-in-down me-2"></i> Barang Masuk</a></li>
                <li><a href="{{ route('admin.barang_keluar.index') }}" class="nav-link @active('admin/barang-keluar*')"><i
                            class="bi bi-box-arrow-up me-2"></i> Barang Keluar</a></li>
                <li><a href="{{ route('admin.stock_opname.index') }}" class="nav-link @active('admin/stock-opname*')"><i
                            class="bi bi-clipboard-check me-2"></i> Stock Opname</a></li>
            </ul>

            <div class="sidebar-heading mt-2">Sistem</div>
            <ul class="nav flex-column">
                <li><a href="{{ route('admin.user.index') }}" class="nav-link @active('admin/user*')"><i
                            class="bi bi-people me-2"></i> User</a></li>
            </ul>

            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="main-content flex-grow-1 p-4">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-0 fw-bold">@yield('page_title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('breadcrumb')</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary">Admin</span>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
