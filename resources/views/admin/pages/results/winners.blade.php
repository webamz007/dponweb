@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Winners</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {!! $html->table(['class' => 'table table-striped'], true) !!}
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <form action="{{ route('pay.all') }}" method="GET">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="btn btn-primary">Pay to all</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {!! $html->scripts() !!}
@endpush
