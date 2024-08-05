@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Delhi Markets Result</h4>
                    </div>
                    <div class="card-header d-flex justify-content-end">
                        <a href="{{ route('result.delhi.create') }}" class="btn btn-primary mr-4">Add New Result</a>
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
@endsection

@push('scripts')
    {!! $html->scripts() !!}
@endpush

@section('js')
    <script>
        $(document).on('click', 'a.result-delete', function(e) {
            e.preventDefault();

            let confirmed = confirm("Are you sure you want to delete this result?");
            if(confirmed) {
                $.ajax({
                    url: $(this).attr('href'),
                    success: function(result) {
                        if(result.success === true) {
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

        $(document).on('click', 'a.result-reverse', function(e) {
            e.preventDefault();
            let confirmed = confirm("Are you sure you want to reverse this result?");
            if(confirmed) {
                var $button = $(this);
                var $loadingIcon = $button.find('.spinner-border');
                var $buttonText = $button.find('.button-text');
                var $loadingText = $button.find('.loading-text');

                // Check if the button is already disabled (AJAX request in progress)
                if ($button.is(':disabled')) {
                    return; // Ignore the click
                }

                // Set the flag to indicate AJAX request is in progress
                var ajaxInProgress = true;

                // Disable the button and show the loading icon and loading text
                $button.attr('disabled', true);
                $buttonText.addClass('d-none');
                $loadingIcon.removeClass('d-none');
                $loadingText.removeClass('d-none');

                $.ajax({
                    url: $button.attr('href'),
                    success: function(result) {
                        if (result.success === true) {
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
                    complete: function() {
                        // Re-enable the button and hide the loading icon and loading text
                        $button.attr('disabled', false);
                        $loadingIcon.addClass('d-none');
                        $loadingText.addClass('d-none');
                        $buttonText.removeClass('d-none');

                        // Reset the flag to indicate AJAX request is complete
                        ajaxInProgress = false;
                    }
                });

                // Prevent any subsequent clicks until the current request completes
                $(document).one('ajaxStop', function() {
                    if (ajaxInProgress) {
                        $button.attr('disabled', true);
                    }
                });
            }
        });
    </script>
@endsection
