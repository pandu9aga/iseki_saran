<!doctype html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Iseki | Saran Perbaikan</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Gradient Able is trending dashboard template made using Bootstrap 5 design framework. Gradient Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="codedthemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" />
    <!-- [Google Font : Poppins] icon -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" /> --}}

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

    @yield('style')

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-header="header-2" data-pc-preset="preset-4" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true"
    data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar ] -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ url('dashboard') }}" class="b-brand text-primary">
                    {{-- <img src="{{ asset('assets/images/logo-white.svg') }}" alt="logo image" class="logo-lg" /> --}}
                    <h5 class="mb-0 text-primary">Saran Perbaikan</h5>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Menu</label>
                    </li>
                    <li class="pc-item {{ $page === 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item {{ $page === 'suggestion' ? 'active' : '' }}">
                        <a href="{{ route('leader.suggestion') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-align-left"></i></span>
                            <span class="pc-mtext">Saran</span>
                        </a>
                    </li>
                    <li class="pc-item {{ $page === 'user' ? 'active' : '' }}">
                        <a href="{{ route('leader.users.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-address-book"></i></span>
                            <span class="pc-mtext">Data User</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-article"></i></span>
                            <span class="pc-mtext">Member</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>User</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('logout') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-power"></i></span>
                            <span class="pc-mtext">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->
    
    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="m-header">
            <a href="{{ asset('dashboard/index.html') }}" class="b-brand text-white">
                <!-- ========   Change your logo from here   ============ -->
                {{-- <img src="{{ asset('assets/images/logo-white.svg') }}" alt="logo image" class="logo-lg" /> --}}
                <h5 class="mb-0 text-white">Saran Perbaikan</h5>
            </a>
        </div>
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <!-- ======= Menu collapse Icon ===== -->
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        {{-- <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . ." />
                                    <button class="btn btn-light-secondary btn-search">Search</button>
                                </div>
                            </form>
                        </div> --}}
                        {{ $user->Name_User }}
                    </li>
                </ul>
            </div>

    </header>
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
            @yield('modal')
        </div>
    </div>

    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm-6 my-1">
                    <p class="m-0">Iseki Saran Perbaikan
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                    </p>
                </div>

            </div>
        </div>
    </footer>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    @yield('script')

    <script>
        layout_change('light');
    </script>

    <script>
        layout_sidebar_change('light');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    {{-- <script>
        preset_change('preset-6');
    </script>

    <script>
        header_change('header-6');
    </script> --}}


</body>
<!-- [Body] end -->

</html>
