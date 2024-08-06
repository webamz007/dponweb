<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="tkc9pE_foc-ek4nMN78JBlfmD6JoHHAeMYDHznK8PbA" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'RK ONLINE') }}</title>
        <!-- Fonts Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
              integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <!-- toaster -->
        <link rel="stylesheet" href="{{ asset('assets/bundles/izitoast/css/iziToast.min.css') }}">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <!-- Scripts -->
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <style>
            .bg-text {
                background: -webkit-linear-gradient(#ffd370 0%, #E6BF1C 50%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                text-transform: uppercase;
            }
            .btn-gradient {
                position: relative;
                display: inline-block;
                font-size: 24px;
                vertical-align: top;
                text-align: center;
                letter-spacing: 1px;
                line-height: 24px;
                color: #000000;
                font-weight: 400;
                padding: 13px 30px;
                border-radius: 35px;
                text-transform: capitalize;
                -webkit-transition: all ease 0.9s;
                -moz-transition: all ease 0.9s;
                -ms-transition: all ease 0.9s;
                -o-transition: all ease 0.9s;
                transition: all ease 0.9s;
                text-shadow: 5px 4px 0 rgba(0, 0, 0, .1);
                background: linear-gradient(to bottom, #E6BF1C 30%, #f7ac03 70%);
                filter: drop-shadow(0 0 5px rgba(101, 40, 193, 0.5));
                box-shadow: 0 0 2px 1px rgba(247, 172, 4, 0.8);
            }
            .btn-gradient:hover {
                background: linear-gradient(to bottom, #e673cf 10%, #9a368a 90%);
                box-shadow: 0 0 3px 1px rgba(152, 51, 134, 0.4);
                text-shadow: 5px 3px 0 #943184;
                filter: drop-shadow(0 0px 20px rgba(152, 51, 134, 0.6));
                color: #fff;
            }
            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
                100% { transform: translateX(0); }
            }

            .animate-shake {
                animation: shake 1.5s infinite;
            }

        </style>
    </head>
    <body class="font-Nunito bg-rk-blue-light" style="margin-bottom: 0;">
        @inertia
        @if (session('status'))
            <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
        @endif

        <script src="{{ asset('assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
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
    </body>
</html>
