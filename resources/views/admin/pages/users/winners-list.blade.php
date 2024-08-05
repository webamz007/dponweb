@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Filter List</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.winners') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input name="date" id="date" type="text" class="form-control datepicker" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Select The Market</label>
                                        <select name="market" id="market" class="form-control select2" required>
                                            @foreach($markets as $market)
                                                <option value="{{ $market->id }}">{{ $market->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Select Session</label>
                                        <select name="market_session" id="session" class="form-control" required>
                                            <option value="open">Open</option>
                                            <option value="close">Close</option>
                                        </select>
                                    </div>
                                    <button type="submit" id="check" class="btn btn-primary">Check</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Winners List</h4>
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
