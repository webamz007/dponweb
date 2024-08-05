@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Starline Ratio Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.star.update') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sd">Single Digit</label>
                                        <input value="{{ $setting->single_digit ?? '' }}" name="single_digit" type="text" class="form-control" id="sg" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sp">Single Pana</label>
                                        <input value="{{ $setting->single_pana ?? '' }}" name="single_pana" type="text" class="form-control" id="sp" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dp">Double Pana</label>
                                        <input value="{{ $setting->double_pana ?? '' }}" name="double_pana" type="text" class="form-control" id="dp" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tp">Tripple Pana</label>
                                        <input value="{{ $setting->tripple_pana ?? '' }}" name="tripple_pana" type="text" class="form-control" id="tp" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

