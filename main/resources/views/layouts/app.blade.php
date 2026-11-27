<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap JavaScript Bundle with Popper -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" 
            crossorigin="anonymous">
        </script>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        
        <!-- 全域共用 CSS -->
        @vite(['resources/css/app.css'])

        <!-- 接收各分頁 @push('styles') -->
        @stack('styles')
    </head>

    <body class="d-flex">
        <!-- sidebar -->
        <aside class="sidebar bg-dark text-white p-3" style="width: 240px; min-height: 100vh;">
            <h5>系統選單</h5>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a href="{{ route('personalinfo') }}" class="nav-link {{ request()->routeIs('personalinfo') ? 'active bg-primary text-white rounded' : 'text-light' }}">
                        個人資料
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('matchrec') }}" class="nav-link {{ request()->routeIs('matchrec') ? 'active bg-primary text-white rounded' : 'text-light' }}">
                        比賽紀錄
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tacticalboard') }}" class="nav-link {{ request()->routeIs('tacticalboard') ? 'active bg-primary text-white rounded' : 'text-light' }}">
                        戰術板
                    </a>
                </li>
            </ul>
        </aside>

        <!-- view -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- navbar -->
            <header class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <span>Volleyball System</span>
                <div class="d-flex align-items-center gap-3">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">登出</button>
                    </form>
                </div>
            </header>

            <!-- content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>