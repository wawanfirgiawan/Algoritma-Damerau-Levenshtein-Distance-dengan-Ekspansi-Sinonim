<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PUSDATA UNSULBAR @yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/Logo5.png') }}">
    <!-- Google Fonts -->
    {{-- <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet"> --}}

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/') }}assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="{{ asset('/') }}assets/css/style.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body>
    @include('layouts.navbar')
    @yield('content')
    @include('layouts.footer')
    <script>
        $('#information').summernote({
            placeholder: 'Masukkan informasi dataset',
            tabsize: 2,
            height: 135,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
            ]
        });
    </script>
    <!-- Vendor JS Files -->
    <script src="{{ asset('/') }}assets/vendor/aos/aos.js"></script>
    <script src="{{ asset('/') }}assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/waypoints/noframework.waypoints.js"></script>
    {{-- <script src="{{ asset('/') }}assets/vendor/php-email-form/validate.js"></script> --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('/') }}assets/js/main.js"></script>
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>
    @if (session('message'))
        <script>
            Swal.fire({
                title: "Bagus!",
                text: "{{ session('message') }}",
                icon: "success"
            });
        </script>
    @endif
    @if ($errors->has('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ $errors->first('error') }}",
            });
        </script>
    @endif
    @yield('scripts')

</body>

</html>
