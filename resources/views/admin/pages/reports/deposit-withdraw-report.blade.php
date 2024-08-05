@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if($reportType == 'deposit')
                            <h4>Deposit Report</h4>
                        @else
                            <h4>Withdraw Report</h4>
                        @endif
                    </div>
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <div class="form-group m-0 mr-3">
                            <input type="date" class="form-control date">
                            <input type="hidden" value="{{ $reportType }}" class="report-type">
                        </div>
                        <a href="{{ route('report.withdraw.export') }}" class="btn btn-primary export-report mr-4">Export</a>
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
        $(document).on('click', 'a.export-report', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                data: { date: $('.date').val(), report_type: $('.report-type').val() },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(result) {
                    var blob = new Blob([result]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "RK Online - Report.xlsx";
                    link.click();
                    iziToast.success({
                        title: 'Success',
                        message: result.msg,
                        position: 'topRight'
                    });
                },
            });
        });
    </script>
@endsection
