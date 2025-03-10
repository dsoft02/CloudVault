<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="dark" data-menu-styles="dark" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ site_title('Download File') }}</title>
    <meta name="description" content="Secure File Storage & Encryption">
    <meta name="author" content="Secure Storage Team">
    <meta name="keywords"
        content="cloud storage, secure file storage, encryption, Laravel, Google Cloud, file security, AES encryption, secure document storage, encrypted cloud, private cloud storage">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
</head>

<body
    style="background-image: url('{{ asset('assets/images/media/landing/81310.jpg') }}');background-repeat: no-repeat;background-position: center;background-size: cover;">
    <div class="page">
        <header class="app-header">
            <div class="main-header-container container-fluid">
                <div class="header-content-left">
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="{{ route('index') }}" class="header-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo"
                                    class="desktop-logo">
                                <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo"
                                    class="toggle-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo"
                                    class="desktop-white">
                                <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo"
                                    class="toggle-white">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="main-content app-content">
            <div class="container">
                <div class="row justify-content-center align-items-center mx-0">
                    <div class="col-xxl-4 col-xl-5 col-lg-5 text-center text-lg-start">
                        <img src="{{ asset('assets/images/media/pngs/download.png') }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-xxl-8 col-xl-7 col-lg-7 pt-5 pb-0 px-lg-2 px-5 text-start">
                        <form action="{{ route('files.shared.download') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $file->share_token }}">
                            <h4 class="text-lg-start fw-bold text-primary">Download File</h4>
                            <p class="text-muted mb-4">Please enter the decryption key to securely download this file.
                            </p>
                            @include('_partials.error-card')
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="d-flex mb-2">
                                        <span>
                                            <i class="bx bxs-badge-check text-primary fs-18"></i>
                                        </span>
                                        <div class="ms-2">
                                            <h6 class="fw-medium mb-0">{{ $file->file_name }} <span
                                                    class="text-muted mb-3">({{ $file->readable_file_size }})</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <h6 class="fw-medium mb-1">Decryption Key</h6>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa fa-lock fs-2"></i></span>
                                        <input type="password" name="decryption_key" class="form-control"
                                            placeholder="Enter decryption key" aria-label="Decryption Key">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="d-flex align-items-center">
                                        <div class="btn-group align-items-center" role="group">
                                            <button type="submit" class="btn btn-lg btn-primary pe-3">Decrypt and Download</button>
                                            <div class="btn btn-dark btn-icon rounded-pill border-white border-2 d-flex align-items-center justify-content-center" style="margin-left: -10px; margin-right: -10px; z-index: 9;" >
                                                <span class="fs-5">or</span>
                                            </div>
                                            <a href="{{ route('files.shared.download.raw', ['token' => $file->share_token]) }}" class="btn btn-lg btn-secondary ps-3">Download Raw</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Internal Under Maintenance JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector("form").addEventListener("submit", function (event) {
                let keyInput = document.querySelector("input[name='decryption_key']").value.trim();

                if (keyInput === "") {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Decryption Key Required',
                        text: 'Please enter a valid decryption key before downloading.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>


</body>

</html>