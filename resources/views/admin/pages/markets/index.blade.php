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
                        @if($type != 'other')
                        <a href="#" class="btn btn-primary mr-4"
                           data-toggle="modal"
                           data-target="#create-starline-market-modal">Add {{ ucfirst($type) }} Market</a>
                        @endif
                        @if($type == 'other')
                        <button type="button" class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#create-market-modal">Add Market</button>
                            @endif
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
    <div class="modal fade" id="edit-market-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
    </div>
    <div class="modal fade" id="create-market-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
        @include('admin.pages.markets.create-modal')
    </div>
    <div class="modal fade" id="create-starline-market-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
        @include('admin.pages.markets.create-market')
    </div>
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush
@section('js')
    <script>
        $(document).on('click', '.create-starline-market', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('market.type.store') }}',
                data: $('#create-market').serialize(),
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#create-starline-market-modal').modal('hide');
                        $('#create-starline-market').trigger('reset');
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
        $(document).on('click', '.add-market, .add-starline-market', function(e) {
            e.preventDefault();
            var formId;
            if ($(this).hasClass('add-market')) {
                formId = '#add-market';
            } else if ($(this).hasClass('add-starline-market')) {
                formId = '#starline-form';
            }
            $.ajax({
                url: '{{ route('market.store') }}',
                data: $(formId).serialize(),
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#create-market-modal').modal('hide');
                        $('#create-starline-market-modal').modal('hide');
                        $('#add-market #market_name').val('');
                        $('#starline-form #market_name').val('');
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
        $(document).on('click', 'a.edit-market', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function(result) {
                    $('#edit-market-modal')
                        .html(result)
                        .modal('show');
                },
            });
        });
        $(document).on('click', '.update-market', function(e) {
            e.preventDefault();
            let id = $("#market_id").val();
            let market_name = $("#market_name").val();
            $.ajax({
                url: "{{ route('market.update') }}",
                data: { "market_id": id, "market_name": market_name },
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $('#edit-market-modal').modal('hide');
                        $('#myForm').trigger('reset');
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
        $(document).on('click', 'a.delete-market', function(e) {
            e.preventDefault();
            let confirmed = confirm("Are you sure you want to delete this market?");
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
        $(function() {
            $('#dataTableBuilder tbody').sortable({
                items: "tr",
                cursor: 'move',
                opacity: 1,
                update: function(event, ui) {
                    let order = [];
                    $('tr.sort-row').each(function(index,element) {
                        order.push({
                            id: $(this).attr('data-id'),
                            position: index+1
                        });
                    });
                    console.log();
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: '{{ route('drag.market') }}',
                        type: 'POST',
                        data: {order: order},
                        success: function(result) {
                            if(result.success === false) {
                                iziToast.error({
                                    title: 'Error',
                                    message: result.msg,
                                    position: 'topRight'
                                });
                            }
                        }
                    });
                }
            }).disableSelection();
        });
    </script>
@endsection
