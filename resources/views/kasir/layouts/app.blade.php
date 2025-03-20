<!DOCTYPE html>
<html lang="en">

<head>
    @include('kasir.partials.head')
</head>

<body>
    <!-- Sidebar -->
    @include('kasir.partials.sidebar')

    <!-- Main Content -->
    <main id="main" class="main">
        @include('kasir.partials.header')
        
        <div class="content">
            @yield('content')
        </div>

        @include('kasir.partials.footer')
    </main>

    @include('kasir.partials.scripts')
</body>
</html>
