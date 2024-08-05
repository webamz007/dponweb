@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Filters</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input name="date" id="date" type="text" class="form-control datepicker" required>
                                </div>
                                <div class="form-group">
                                    <label for="market">Select The Market</label>
                                    <select name="market" id="market" class="form-control select2" required>
                                        @foreach($markets as $market)
                                            <option value="{{ $market->id }}">{{ $market->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type">Select Type</label>
                                    <select name="type" id="type" class="form-control select2" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->bid_type }}">
                                                {{ str_replace('_', ' ', Str::title($type->bid_type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="session">Select Session</label>
                                    <select name="market_session" id="session" class="form-control" required>
                                        <option value="open">Open</option>
                                        <option value="close">Close</option>
                                    </select>
                                </div>
                                <button type="button" id="check" class="btn btn-primary">Check</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Market Types</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ajax_view"
                                   id="table1">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Equal</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-gray font-17 text-left footer-total">
                                        <td colspan="2"><strong>Total :</strong> </td>
                                        <td><strong><span class="total_amount">0</span></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Market Type</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ajax_view"
                                   id="table2">
                                <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Equal</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr class="bg-gray font-17 text-left footer-total">
                                    <td colspan="2"><strong>Total :</strong> </td>
                                    <td><strong><span class="type_amount">0</span></strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                // "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax: {
                    url: "{{ route('report.general') }}",
                    type: 'GET',
                    async: true,
                    data: function(d) {
                        d.date = $("#date").val();
                        d.market = $('select#market').val();
                        d.session = $('select#session').val();
                        d.type = $('select#type').val();
                    }
                },
                'columns' : [
                    { data: "bid_type", "name": "bid_type", },
                    { data: "equal", "searchable": false, "sortable": false, },
                    { data: "amount", "searchable": false },
                ],
                "footerCallback": function ( row, data, start, end, display ) {

                    var amount = 0;
                    for (r in data) {
                        amount += parseInt(data[r].amount);
                    }
                    $('.total_amount').text(amount);
                }
            };

            var config2 = {
                processing: true,
                serverSide: true,
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                responsive: true,
                autoWidth:false,
                // "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax: {
                    url: "{{ route('report.types') }}",
                    type: 'GET',
                    async: true,
                    data: function(d) {
                        d.date = $("#date").val();
                        d.market = $('select#market').val();
                        d.session = $('select#session').val();
                        d.type = $('select#type').val();
                    }
                },
                'columns' : [
                    { data: "number", "name": "number", },
                    { data: "equal", "searchable": false, "sortable": false, },
                    { data: "amount", "searchable": false },
                ],
                "footerCallback": function ( row, data, start, end, display ) {

                    var amount = 0;
                    for (r in data) {
                        amount += parseInt(data[r].amount);
                    }
                    $('.type_amount').text(amount);
                }
            };

            let market_types = $('#table1').DataTable( config );
            let types = $('#table2').DataTable( config2 );
            $("#check").on('click', function (){
                market_types.ajax.reload();
                types.ajax.reload();
            });
        });
    </script>
@endsection
