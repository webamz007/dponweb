@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Agents</h4>
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
    <div class="modal fade" id="deposit-withdraw-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
    </div>
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush
@section('js')
    <script>
        $(document).on('click', 'a.mark-agent', function(e) {
            e.preventDefault();
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
        });
        $(document).on('click', '.deposit-withdraw-point', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success: function(result) {
                    $('#deposit-withdraw-modal').html(result).modal('show');
                },
            });
        });
        $(document).on('click', '.update-points', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('user.points.update') }}',
                data: $('#deposit-withdraw-form').serialize(),
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#deposit-withdraw-modal').modal('hide');
                        $('#deposit-withdraw-form').trigger('reset');
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
        $(document).on('click', '.delete-user', function(e) {
            e.preventDefault();

            let confirmed = confirm("Are you sure you want to delete this agent?");
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
    </script>
@endsection
