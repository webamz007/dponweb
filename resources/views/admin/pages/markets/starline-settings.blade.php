@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Starline Markets Setting</h4>
                    </div>
                    <div class="card-header d-flex justify-content-end">
                        <button type="button" class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#create-starline-market-modal">Add Starline Market</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ajax_view"
                                   id="table1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                        <th>Saturday</th>
                                        <th>Sunday</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="create-starline-market-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
        @include('admin.pages.markets.create-market')
    </div>
    <div class="modal fade" id="edit-game-settings-modal" tabindex="-1" role="dialog" aria-labelledby="formModal"
         aria-hidden="true">
    </div>
@endsection

@section('js')
    <script>
        $(function(){
            var config = {
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                responsive: true,
                autoWidth:false,
                ajax: {
                    url: "{{ route('starline.settings') }}",
                    type: 'GET',
                    async: true,
                },
                'columns' : [
                    {'data': 'name', 'name': 'name'},
                    {'data': 'monday', 'searchable': false},
                    {'data': 'tuesday', 'searchable': false},
                    {'data': 'wednesday', 'searchable': false},
                    {'data': 'thursday', 'searchable': false},
                    {'data': 'friday', 'searchable': false},
                    {'data': 'saturday', 'searchable': false},
                    {'data': 'sunday', 'searchable': false},
                ]
            };

            $('#table1').DataTable( config );
        });
        $(document).on('click', '.add-starline-market', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('market.store') }}',
                data: $('#starline-form').serialize(),
                success: function(result) {
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#table1').DataTable().ajax.reload();
                        $('#create-starline-market-modal').modal('hide');
                        $('#starline-form').trigger('reset');
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
                    if(result.success === true) {
                        iziToast.success({
                            title: 'Success',
                            message: result.msg,
                            position: 'topRight'
                        });
                        $('#table1').DataTable().ajax.reload();
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
        });
    </script>
@endsection
