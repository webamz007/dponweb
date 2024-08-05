<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin - Dashboard</title>
{{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('assets/img/favicon.ico') }}' />
    <!-- Datatables Links -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <!-- Toaster -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/izitoast/css/iziToast.min.css') }}">
    <!-- Select2 Css -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
    <!-- Timepicker Css -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="{{ asset('https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css') }}">

    @yield('css')
    <style>
        table th,td {
            white-space: nowrap;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
        }
    </style>
</head>

<body>
<div class="loader"></div>
<div id="app">
    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
    @endif
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        @include('admin.includes.navbar')
        @include('admin.includes.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
            @include('admin.includes.setting')
        </div>
        @include('admin.includes.footer')
    </div>
</div>

<!-- General JS Scripts -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/index.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<!-- Custom JS File -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<!-- JS Libraies -->
<script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<!-- jQuery UI Touch Punch -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/datatables.js') }}"></script>
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

        // Change Withdraw Status
        $(document).on('click', 'a.withdraw-status', function(e) {
            e.preventDefault();
            let confirmed = confirm("Are you sure you want to update withdraw status?");
            if(confirmed) {
                let $this = $(this);
                $.ajax({
                    url: $this.attr('href'),
                    data: {status: $this.attr('data-status')},
                    success: function(result) {
                        if(result.success === true) {
                            $this.attr('data-status', result.status);
                            let btn_txt = (result.status === 1) ? 'Withdraw Is Active' : 'Withdraw Is UnActive';
                            $this.text(btn_txt);

                            iziToast.success({
                                title: 'Success',
                                message: result.msg,
                                position: 'topRight'
                            });
                            $('#dataTableBuilder').DataTable().ajax.reload();
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: result.msg,
                                position: 'topRight'
                            });
                        }
                    },
                });
            }
        });
    });
</script>

@stack('scripts')

@yield('js')

</body>

</html>
