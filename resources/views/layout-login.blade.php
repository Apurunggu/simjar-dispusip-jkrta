<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMJAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow: hidden;
            background: #f5f6fa;
        }
        body {
            background: none !important;
        }
        /* Container utama dengan border-radius dan shadow */
        .auth-outer-container {
            width: 98vw;
            height: 96vh;
            min-height: 600px;
            min-width: 320px;
            max-width: 1200px;
            max-height: 800px;
            margin: 1.5vh auto;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 8px 48px 0 rgba(31, 38, 135, 0.18), 0 1.5px 8px 0 rgba(33,150,243,0.10);
            background: #fff;
            position: relative;
            display: flex;
        }
        /* .login-split dan child-nya edge-to-edge, full height */
        .login-split {
            display: flex;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 1;
        }
        .login-left, .login-right {
            height: 100%;
            min-height: 0;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Hilangkan navbar pada halaman login/register */
        .navbar { display: none !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-outer-container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
