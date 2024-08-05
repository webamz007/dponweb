@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Ratio Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.ratio.update') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sd">Single Digit</label>
                                        <input value="{{ $setting->single_digit ?? '' }}" type="text" name="single_digit" class="form-control" id="sg" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dd">Double Digit</label>
                                        <input value="{{ $setting->double_digit ?? '' }}" type="text"  name="double_digit" class="form-control" id="dd" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sp">Single Pana</label>
                                        <input value="{{ $setting->single_pana ?? '' }}" type="text" name="single_pana" class="form-control" id="sp" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dp">Double Pana</label>
                                        <input value="{{ $setting->double_pana ?? '' }}" type="text" name="double_pana" class="form-control" id="dp" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tp">Tripple Pana</label>
                                        <input value="{{ $setting->tripple_pana ?? '' }}" type="text" name="tripple_pana" class="form-control" id="tp" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hs">Half Sangam</label>
                                        <input value="{{ $setting->half_sangum ?? '' }}" type="text" name="half_sangum" class="form-control" id="hs" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fs">Full Sangam</label>
                                        <input value="{{ $setting->full_sangum ?? '' }}"  type="text" name="full_sangum" class="form-control" id="fs" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

