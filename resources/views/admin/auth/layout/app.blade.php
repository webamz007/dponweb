<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-social/bootstrap-social.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/favicon.ico') }}" />
    <!-- Toaster -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/izitoast/css/iziToast.min.css') }}">
</head>

<body>
<div class="loader"></div>
<div id="app">
    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
    @endif
    @yield('content')
</div>
<!-- General JS Scripts -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<!-- JS Libraries -->
<!-- Page Specific JS File -->
<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<!-- Custom JS File -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<!-- Toaster JS File -->
<script src="{{ asset('assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
</body>

<script>
    $(document).ready(function (){
        if ($('#status_span').length) {
            var status = $('#status_span').attr('data-status');
            if (status === '1') {
                iziToast.success({
                    title: 'Success',
                    message: $('#status_span').attr('data-msg'),
                    position: 'topRight'
                });
            } else if (status == '' || status === '0') {
                iziToast.error({
                    title: 'Error',
                    message: $('#status_span').attr('data-msg'),
                    position: 'topRight'
                });
            }
        }
    });
</script>

</html>
