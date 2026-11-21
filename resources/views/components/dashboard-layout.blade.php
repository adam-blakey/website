@props(['title', 'pretitle' => 'Dashboard', 'actions' => null])

<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} · Dashboard</title>
    <link type="image/x-icon" href="{{ asset('favicon.ico') }}" rel="icon">
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>

<body>
    <div class="page">
        <header class="navbar navbar-expand-sm d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-nav" aria-controls="dashboard-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand pe-0 pe-md-3" href="{{ route('dashboard.overview') }}">Dashboard</a>
                <div class="navbar-nav flex-row order-md-last">
                    <a class="nav-link" href="{{ route('index') }}">View site</a>
                </div>
                <div class="collapse navbar-collapse" id="dashboard-nav">
                    <ul class="navbar-nav">
                        @foreach ([
                            'dashboard.overview' => ['Overview', 'dashboard.overview'],
                            'dashboard.posts.index' => ['Posts', 'dashboard.posts.*'],
                            'dashboard.concerts.index' => ['Concerts', 'dashboard.concerts.*'],
                            'dashboard.schedule' => ['Schedule', 'dashboard.schedule'],
                        ] as $route => [$label, $activePattern])
                            <li @class(['nav-item', 'active' => request()->routeIs($activePattern)])>
                                <a class="nav-link" href="{{ route($route) }}" @if (request()->routeIs($activePattern)) aria-current="page" @endif>
                                    <span class="nav-link-title">{{ $label }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">{{ $pretitle }}</div>
                            <h1 class="page-title">{{ $title }}</h1>
                        </div>
                        @if ($actions)
                            <div class="col-auto ms-auto">
                                <div class="btn-list">{{ $actions }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <main class="page-body">
                <div class="container-xl">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
