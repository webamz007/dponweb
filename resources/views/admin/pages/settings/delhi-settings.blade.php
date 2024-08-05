@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Delhi Ratio Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.delhi.update') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ander">Ander</label>
                                        <input value="{{ $setting->ander ?? '' }}" name="ander" type="text" class="form-control" id="ander" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="baher">Baher</label>
                                        <input value="{{ $setting->baher ?? '' }}" name="baher" type="text" class="form-control" id="baher" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jodi">Jodi</label>
                                        <input value="{{ $setting->jodi ?? '' }}" name="jodi" type="text" class="form-control" id="jodi" required>
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

