<!DOCTYPE html>
<html lang="ko" data-bs-theme="light">
<head>
    <script>
        (function(){
            var s = localStorage.getItem('bs-theme');
            var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-bs-theme', s || (d ? 'dark' : 'light'));
        })();
    </script>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        [data-bs-theme="dark"] .wrap { background-color: var(--bs-body-bg); min-height: 100vh; }
        [data-bs-theme="dark"] .navbar { --bs-navbar-color: rgba(255,255,255,.85); --bs-navbar-hover-color: #fff; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/xeicon/xeicon.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="wrap">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-body">
            @include("components.nav")
        </nav>
        <div class="container mt-5">
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script>
        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('bs-theme', theme);
        }
        function toggleTheme() {
            var current = document.documentElement.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            setTheme(next);
            document.querySelectorAll('.theme-icon-dark').forEach(function(el) { el.style.display = next === 'dark' ? '' : 'none'; });
            document.querySelectorAll('.theme-icon-light').forEach(function(el) { el.style.display = next === 'dark' ? 'none' : ''; });
            return next;
        }
    </script>
</body>
</html>
