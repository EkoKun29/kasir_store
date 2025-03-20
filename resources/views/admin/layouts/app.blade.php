<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.head')
</head>

<body>
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main id="main" class="main">
        @include('admin.partials.header')
        
        <div class="content">
            @yield('content')
        </div>

        @include('admin.partials.footer')
    </main>

    @include('admin.partials.scripts')
</body>
</html>
