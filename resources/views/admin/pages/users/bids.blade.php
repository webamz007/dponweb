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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Select User Phone</label>
                                    <select name="phone" id="phone" class="form-control select2" required>
                                        <option value="">-- Select Phone --</option>
                                        @foreach($phones as $phone)
                                            <option value="{{ $phone->phone }}">{{ $phone->phone }}</option>
                                        @endforeach
                                    </select>
                                    @if($userBids == 'user')
                                        <input type="hidden" value="{{ $userId }}" name="user_id" id="user_id">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="market">Select Market</label>
                                <select name="market" id="market" class="form-control select2" required>
                                    <option value="">-- Select Market --</option>
                                    @foreach($markets as $market)
                                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Bids Management</h4>
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
@php
    if ($userBids == 'user') {
        $route = 'users.bids';
    }else {
        $route = 'bids.all';
    }
@endphp
@push('scripts')
    {!! $html->scripts() !!}
@endpush

@section('js')
    <script>
        $(document).on('change', '#phone, #market', function (){
            let phone = $('select#phone').val();
            let market = $('select#market').val();
            let userId = $('#user_id').val();
            var url = '{{ route($route) }}?phone=' + encodeURIComponent(phone) + '&market=' + encodeURIComponent(market) + '&user_id=' + encodeURIComponent(userId);
            LaravelDataTables.dataTableBuilder.ajax.url(url)
            LaravelDataTables.dataTableBuilder.ajax.reload()
        });
    </script>
@endsection
