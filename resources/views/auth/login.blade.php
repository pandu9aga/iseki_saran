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

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-header="header-4" data-pc-preset="preset-4" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true"
    data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main v1 bg-brand-color-6">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            {{-- <img src="{{ asset('assets/img/logo.png') }}"" /> --}}
                            <h4 class="f-w-500 mb-1 text-primary"><b>Iseki Saran</b></h4>
                        </div>
                        <!-- Tabs -->
                        <section class="mt-4 py-1">
                            <div class="container">
                                <div class="row">
                                    <div class="mx-auto">
                                        <div class="nav-wrapper position-relative end-0">
                                            <ul class="nav nav-pills nav-fill p-1 bg-light rounded" role="tablist">
                                                <li class="nav-item">
                                                    <a id="show-member" class="nav-link mb-0 px-0 py-1 active fw-bold" 
                                                        data-bs-toggle="tab" 
                                                        href="#profile-tabs-simple" 
                                                        role="tab" 
                                                        aria-controls="profile" 
                                                        aria-selected="true">
                                                        <span id="item-member" class="text-white">Member</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="show-admin" class="nav-link mb-0 px-0 py-1 fw-bold" 
                                                        data-bs-toggle="tab" 
                                                        href="#dashboard-tabs-simple" 
                                                        role="tab" 
                                                        aria-controls="dashboard" 
                                                        aria-selected="false">
                                                        <span id="item-admin">Admin</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Forms wrapper -->
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="text-danger mb-2">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif

                            <div class="position-relative" style="overflow: hidden; height: auto;">
                                <div class="form-slider d-flex transition-slide" style="width: 200%;">

                                    <!-- Member Form -->
                                    <form id="member-form" class="text-start w-100 px-2" action="{{ route('login.member') }}" method="POST">
                                        @csrf
                                        <h5 class="text-primary mb-4">Login Member</h5>
                                        <label class="form-label">NIK</label>
                                        <div class="input-group input-group-outline">
                                            <input type="text" id="NIK_Input" name="NIK_Member" class="form-control">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-100 my-4 mb-2">Login</button>
                                        </div>
                                        <br>
                                        <div>
                                            <button id="scanNIK" type="button" class="btn btn-primary w-100">Scan</button>
                                        </div>
                                        <div id="reader_nik" style="width: 100%; margin-top: 20px;"></div>
                                    </form>

                                    <!-- Admin Form -->
                                    <form id="admin-form" class="text-start w-100 px-2" action="{{ route('login.auth') }}" method="POST">
                                        @csrf
                                        <h5 class="text-primary mb-4">Login Admin</h5>
                                        <label class="form-label">Username</label>
                                        <div class="input-group input-group-outline">
                                            <input type="text" name="Username_User" class="form-control">
                                        </div>
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-outline mb-3">
                                            <input type="password" name="Password_User" class="form-control">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-100 my-4 mb-2">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>


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
        preset_change('preset-4');
    </script>

    <script>
        header_change('header-1');
    </script> --}}

    <style>
    .form-slider {
      transition: transform 0.5s ease-in-out;
    }
    .slide-left {
      transform: translateX(-50%);
    }
  </style>

  <script>
    const slider = document.querySelector('.form-slider');
    const showAdminBtn = document.getElementById('show-admin');
    const showMemberBtn = document.getElementById('show-member');
    const showAdminItem = document.getElementById('item-admin');
    const showMemberItem = document.getElementById('item-member');

    showAdminBtn.addEventListener('click', (e) => {
      e.preventDefault();
      slider.classList.add('slide-left');
      showAdminBtn.classList.add('active');
      showMemberBtn.classList.remove('active');

      showAdminItem.classList.add('text-white');
      showAdminItem.classList.remove('text-primary');
      showMemberItem.classList.remove('text-white');
      showMemberItem.classList.add('text-primary');
    });

    showMemberBtn.addEventListener('click', (e) => {
      e.preventDefault();
      slider.classList.remove('slide-left');
      showMemberBtn.classList.add('active');
      showAdminBtn.classList.remove('active');

      showMemberItem.classList.add('text-white');
      showMemberItem.classList.remove('text-primary');
      showAdminItem.classList.remove('text-white');
      showAdminItem.classList.add('text-primary');
    });
  </script>
  <!-- QR Code Library -->
    <script src="{{ asset('assets/js/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/qrcode.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> --}}

    <!-- QR Code Generation Script -->
    <!-- QR Code Scan NIK -->
    <script>
        var element = document.getElementById('member-form');
        var width = element.offsetWidth;

        const nikScanner = new Html5QrcodeScanner("reader_nik", {
            fps: 10,
            qrbox: {
                width: width,
                height: width,
            },
        });

        function onScanSuccess(decodedText, decodedResult) {
            // Ambil bagian pertama dari decodedText sebelum ;
            const nik = decodedText.split(';')[0].trim();

            // Isi input NIK
            const input = document.getElementById("NIK_Input");
            input.value = nik;

            // Hapus scanner
            nikScanner.clear();

            // Submit form
            document.getElementById("memberLoginForm").submit();
        }

        document.getElementById("scanNIK").addEventListener("click", () => {
            nikScanner.render(onScanSuccess);
        });
    </script>

</body>
<!-- [Body] end -->

</html>
