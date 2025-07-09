<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="STMIK Bandung">
    <title>@yield('title', 'STMIK Bandung - Admin Panel')</title>

    {{-- Favicons --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
    <meta name="theme-color" content="#ffffff">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- Feather Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    {{-- JQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        body {
            display: flex;
            height: 100vh;
        }

        #content {
            flex-grow: 1;
            padding: 20px;
            background: #F6F6F6;
        }

        .sidebar-icon {
            margin-right: 10px;
        }

        #sidebar.collapsed .sidebar-text {
            display: none;
        }

        #sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }
    </style>
    
    @stack('styles')
</head>

<body>

    @if (isset($role) && $role == 'user')
        @include('components.sidebar-user')
    @else
        @include('components.sidebar')
    @endif

    {{-- Content Area --}}
    <div id="content">
        <div class="admin-content">
            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-white background-white">
                <div class="container-fluid">
                    <span class="navbar-brand font-weight-bold fw-bold">@yield('title')</span>
                    {{-- <button class="btn btn-outline-dark" onclick="logout()">
                        <i data-feather="log-out"></i> Logout
                    </button> --}}
                </div>
            </nav>

            {{-- Main Content --}}
            <div style="padding: 10px">
                <main class="mt-2 container-fluid">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- Toast --}}
    @include('components.toast')

    {{-- Feather Icons --}}
    <script>
        feather.replace();

        // Sidebar Toggle
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Logout function (example)
        function logout() {
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, logout!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            });
        }
    </script>

    @include('components.delete-confirm')

    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "#96c93d",
                },
            }).showToast();
        </script>
    @endif

    @if (session('error'))
        <script>
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "#e74c3c",
                },
            }).showToast();
        </script>
    @endif

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                Toastify({
                    text: "{{ $error }}",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "#e74c3c",
                    },
                }).showToast();
            @endforeach
        </script>
    @endif

    @stack('scripts')
</body>

</html>
