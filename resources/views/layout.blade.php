<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMJAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .badge.bg-orange {
            background-color: orange !important;
            color: #fff !important;
        }
        .badge.bg-purple {
            background-color: #7c3aed !important;
            color: #fff !important;
        }
        .badge.bg-yellow {
            background-color: #ffe066 !important;
            color: #fff !important;
        }
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
        }

        body {
            background-color: #ecf0f1;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .sidebar {
            background-color: #34495e;
            min-height: calc(100vh - 56px);
            padding-top: 20px;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar a.active {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        .main-content {
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            border-radius: 8px 8px 0 0 !important;
        }

        .stat-card {
            text-align: center;
            padding: 25px;
            border-left: 4px solid var(--secondary-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .btn-custom {
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s;
        }

        .alert {
            border-radius: 4px;
            border: none;
        }

        /* Status Badge Styling */
        .badge-status-aktif {
            background-color: #27ae60 !important;
            color: white !important;
            font-weight: 500;
        }

        .badge-status-tidak-aktif {
            background-color: #e74c3c !important;
            color: white !important;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                padding: 0;
            }

            .sidebar a {
                display: inline-block;
                margin-right: 10px;
                padding: 8px 12px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-diagram-3"></i> SIMJAR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">{{ date('d F Y') }}</span>
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text">
                                    Role: <strong>{{ optional(auth()->user()->role)->label ?? 'N/A' }}</strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h6 class="text-white px-3 mb-3">MENU UTAMA</h6>
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <hr class="bg-white opacity-10">
                <h6 class="text-white px-3 mb-3 mt-3">MODUL</h6>
                <a href="{{ route('barang-masuk.index') }}">
                    <i class="bi bi-box-seam"></i> Barang Masuk
                </a>
                <a href="{{ route('distribusi.index') }}">
                    <i class="bi bi-truck"></i> Distribusi Barang
                </a>
                <a href="{{ route('perangkat-jaringan.index') }}">
                    <i class="bi bi-router"></i> Perangkat Jaringan
                </a>

                <a href="{{ route('draft-dokumen-distribusi.index') }}" title="Draft Dokumen Distribusi">
                    <i class="bi bi-file-earmark-pdf"></i> Draft Dokumen Distribusi
                </a>

                <a href="{{ route('laporan-ttd.index') }}" title="Dokumen Barang">
                    <i class="bi bi-file-earmark-arrow-down"></i> Dokumen Barang
                </a>
                <a href="{{ route('distribusi.activity-report') }}">
                    <i class="bi bi-list-check"></i> Laporan Aktivitas Distribusi
                </a>

                @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                    <hr class="bg-white opacity-10">
                    <h6 class="text-white px-3 mb-3 mt-3">KONTROL AKSES</h6>
                    <a href="{{ route('user-management.index') }}">
                        <i class="bi bi-people"></i> Manajemen User
                    </a>
                @endif
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi Kesalahan!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
