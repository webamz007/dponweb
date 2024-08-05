@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Markets</h4>
                    </div>
                    <div class="card-header d-flex justify-content-end">
                        @php
                        $status = \App\Models\MarketDetail::where('status', 'true')->exists();
                        if ($status) {
                            $status = 'true';
                        } else {
                            $status = 'false';
                        }
                        @endphp
                        <form action="{{ route('markets.status') }}" method="GET">
                            <input type="hidden" name="status" value="{{ $status }}">
                            @csrf
                            @if($status == 'true')
                                <button type="submit" class="btn btn-danger mr-3">
                                    Close All Markets
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary mr-3">
                                    Open All Markets
                                </button>
                            @endif
                        </form>
                        <button type="button" class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#create-market-modal">Add Market</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {!! $html->table(['class' => 'table table-striped'], true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="create-market-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
        @include('admin.pages.markets.create-modal')
    </div>
    <div class="modal fade" id="edit-game-settings-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
    </div>
@endsection

@push('scripts')
    {!! $html->scripts() !!}
@endpush

@section('js')
    <script>
        $(document).on('click', '.add-market', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('market.store') }}',
                data: $('#add-market').serialize(),
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#create-market-modal').modal('hide');
                        $('#add-market').trigger('reset');
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: result.msg,
                            position: 'topRight'
                        });
                    }
                },
            });
        });
        $(document).on('click', 'a.edit-game-settings', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function(result) {
                    $('#edit-game-settings-modal')
                        .html(result)
                        .modal('show');
                    $(".timepicker").timepicker({
                        icons: {
                            up: "fas fa-chevron-up",
                            down: "fas fa-chevron-down"
                        }
                    });
                },
            });
        });
        $(document).on('click', '.update-game-settings', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('game.settings.update') }}",
                data: $('#update-game-settings').serialize(),
                success: function(result) {
                    if (result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#edit-game-settings-modal').modal('hide');
                        $('#update-game-settings').trigger('reset');
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: result.msg,
                            position: 'topRight'
                        });
                    }
                },
            });

            // var openTime = $('#open_time').val();
            // var closeTime = $('#close_time').val();
            // var openResult = $('#open_result').val();
            // var closeResult = $('#close_result').val();
            //
            // var isTimeValid = isCloseTimeValid(openTime, closeTime);
            // var isResultTimeValid = isCloseTimeValid(openResult, closeResult);
            //
            //
            // // Check if close time is valid
            // if (isTimeValid && isResultTimeValid) {
            //     // Proceed with the AJAX request
            //
            // } else {
            //     var errorMessage = 'Errors:';
            //
            //     if (!isTimeValid) {
            //         errorMessage += '\n- Close end time should be at least 5 minutes later than open end time.';
            //     }
            //
            //     if (!isResultTimeValid) {
            //         errorMessage += '\n- Close end result should be different from open end result.';
            //     }
            //
            //     iziToast.error({
            //         title: 'Error',
            //         message: errorMessage,
            //         position: 'topRight'
            //     });
            // }
        });


        function isCloseTimeValid(openTime, closeTime) {
            console.log(openTime);
            console.log(closeTime);
            // Parse and convert time strings to Moment.js objects
            var openTimeMoment = moment(openTime, 'h:mm A');
            var closeTimeMoment = moment(closeTime, 'h:mm A');

            // Calculate the time difference in milliseconds
            var timeDifference = closeTimeMoment.diff(openTimeMoment);

            // Check if close time is at least 5 minutes later than open time
            return timeDifference >= 5 * 60 * 1000;
        }

        var openTime = '5:30 PM';
        var closeTime = '6:00 PM';
        // var isValid = isCloseTimeValid(openTime, closeTime);
        // console.log('Is Close Time Valid:', isValid);

    </script>
@endsection
