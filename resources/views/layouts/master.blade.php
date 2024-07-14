<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>{{ config('app.name', 'Card App') }}</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        
        <!-- Favicons -->
        <link href="{{ asset('img/muc-favicon.png') }}" rel="icon">
        <link href="{{ asset('img/muc-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


        <!-- Template Main CSS File -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

        <!-- DataTables Responsive JS -->
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        <!-- Quill JS (CDN) -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

        @stack('css')

    </head>

    <body>
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <i class="bi bi-list toggle-sidebar-btn"></i>
        
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('card.index') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('img/muc-logo.png') }}" alt="">
                <span class="d-none d-lg-block">MUC Consulting</span>
            </a>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('img/default-profile-img.jpeg') }}" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Image Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ Auth::user()->role }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-item d-flex align-items-center">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" class="d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('card.index') ? 'active' : '' }}" href="{{ route('card.index') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('card.manage') ? 'active' : '' }}" href="{{ route('card.manage') }}">
            <i class="bx bxs-customize"></i>
            <span>Manage e-Card</span>
            </a>
        </li><!-- End Manage Nav -->

        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('employees.list') ? 'active' : '' }}" href="{{ route('employees.list') }}">
            <i class="bi bi-card-list"></i>
            <span>List Data</span>
            </a>
        </li><!-- End List Data Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('employees.import') ? 'active' : '' }}" href="{{ route('employees.import') }}">
            <i class="bx bx-import"></i>
            <span>Import Data</span>
            </a>
        </li><!-- End Import Data Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('card.add') ? 'active' : '' }}" href="{{ route('card.add') }}">
            <i class="ri-play-list-add-fill"></i>
            <span>Add Data</span>
            </a>
        </li><!-- End Add Data Nav -->

        <li class="nav-heading">User</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('user.profile') ? 'active' : 'collapsed' }}" href="{{ route('user.profile') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a class="nav-link collapsed" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
            </a>
        </li><!-- End Sign Out Nav -->
        </ul>

        </aside><!-- End Sidebar-->

        <!-- menampilkan konten -->
        <main id="main" class="main">
        <div class="container mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        @yield('content')
        @yield('scripts')

        </main>

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div class="copyright">
            &copy; Copyright <strong><span>MUC Consulting</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <!-- Vendor JS Files -->
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
        <!-- <script src="{{ asset('vendor/quill/quill.min.js') }}"></script> -->
        <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

        <!-- Template Main JS File -->
        <script src="{{ asset('js/main.js') }}"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

        <!-- Popper.js (diperlukan untuk Bootstrap) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

        @stack('js')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var alerts = document.querySelectorAll('.alert');
                    alerts.forEach(function(alert) {
                        var alertInstance = bootstrap.Alert.getOrCreateInstance(alert);
                        alertInstance.close();
                    });
                }, 5000); // 3000ms = 3 detik
            });
        </script>

    </body>
</html>