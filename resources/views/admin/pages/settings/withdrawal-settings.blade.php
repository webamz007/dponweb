@extends('admin.layout.app')

@section('css')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #6777ef;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Withdrawal Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update-withdrawal-settings') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control timepicker" name="start_time" value="{{ optional($withdrawal_settings)->start_time ? \Illuminate\Support\Carbon::parse($withdrawal_settings->start_time)->format('g:i A') : '' }}" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control timepicker" name="end_time" value="{{ optional($withdrawal_settings)->end_time ? \Illuminate\Support\Carbon::parse($withdrawal_settings->end_time)->format('g:i A') : '' }}" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Withdraw Closed Days</label>
                                        <select class="form-control select2" multiple name="days_of_week[]">
                                            @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                                                <option value="{{ $day }}" {{ is_array($savedDays) && in_array($day, $savedDays) ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary">Update Withdrawal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

