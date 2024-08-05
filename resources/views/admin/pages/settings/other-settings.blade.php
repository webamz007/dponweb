@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Other Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.other.update') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="control-label">UPI Switch</div>
                                        <label class="custom-switch mt-2 pl-0">
                                            <input {{ ($setting->isUpi) ? 'checked' : '' }} type="checkbox" name="isUpi" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vpa">VPA</label>
                                        <input value="{{ $setting->vpa }}" type="text" name="vpa" class="form-control" id="vpa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input value="{{ $setting->name }}" type="text" name="name" class="form-control" id="name">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="merchant_key">Merchant</label>
                                        <input value="{{ $setting->merchant_key }}" type="text" name="merchant_key" class="form-control" id="merchant_key">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="control-label">PayuMoney Switch</div>
                                        <label class="custom-switch mt-2 pl-0">
                                            <input {{ ($setting->isUMoney) ? 'checked' : '' }} type="checkbox" name="isUMoney" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="salt_key">Salt</label>
                                        <input value="{{ $setting->salt_key }}" type="text" name="salt_key" class="form-control" id="salt_key">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="guessing">Guessing URL</label>
                                        <input value="{{ $setting->guessing }}" type="text" name="guessing" class="form-control" id="merchant">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url">Website URL</label>
                                        <input value="{{ $setting->website }}" type="text" name="website" class="form-control" id="url">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="control-label">PhonePe Switch</div>
                                        <label class="custom-switch mt-2 pl-0">
                                            <input {{ ($setting->isPhonePe) ? 'checked' : '' }} type="checkbox" name="isPhonePe" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phonepe_key">PhonePe Key</label>
                                        <input value="{{ $setting->phonepe_key }}" type="text" name="phonepe_key" class="form-control" id="phonepe_key">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input value="{{ $setting->phone }}" type="text" name="phone" class="form-control" id="phone">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="whatsapp">WhatsApp</label>
                                        <input value="{{ $setting->whatsapp }}" type="text" name="whatsapp" class="form-control" id="whatsapp">
                                    </div>
                                </div>
                                <div class="col-md-3 offset-md-3">
                                    <div class="form-group">
                                        <label for="results_and_videos">Result & Videos</label>
                                        <input value="{{ $setting->results_and_videos }}" type="text" name="results_and_videos" class="form-control" id="results_and_videos">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="whatsapp_channel">WhatsApp Channel</label>
                                        <input value="{{ $setting->whatsapp_channel }}" type="text" name="whatsapp_channel" class="form-control" id="whatsapp_channel">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="withdraw_limit">Withdraw Limit</label>
                                        <input value="{{ $setting->withdraw_limit }}" type="number" name="withdraw_limit" class="form-control" id="withdraw_limit" required>
                                    </div>
                                </div>
                                <div class="col offset-md-3">
                                    <div class="form-group">
                                        <label for="razorpay_key_id">RazorPay Key ID</label>
                                        <input value="{{ $setting->razorpay_key_id }}" type="text" name="razorpay_key_id" class="form-control" id="razorpay_key_id">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="razorpay_secret_key">RazorPay Secret Key</label>
                                        <input value="{{ $setting->razorpay_secret_key }}" type="text" name="razorpay_secret_key" class="form-control" id="razorpay_secret_key">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

