@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Result</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('result.store') }}" method="GET">
                            @csrf
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" id="date" class="form-control datepicker" required />
                                <input type="hidden" name="market_type" value="{{ $market_type }}">
                            </div>
                            <div class="form-group">
                                <label for="market">Select the market</label>
                                <select name="market" class="form-control select2" id="market" required>
                                    @foreach($markets as $market)
                                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($market_type == 'other')
                            <div class="form-group">
                                <label for="status">Select Session</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Select Session</option>
                                    <option value="open">Open</option>
                                    <option value="close">Close</option>
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="status" value="open">
                            @endif
                            <div class="form-group">
                                <label for="digit1">Digit 1</label>
                                <input onchange="resultCal()" type="number" name="digit1" min="0" placeholder="Type Digit 1" class="form-control" id="digit1" />
                            </div>
                            <div class="form-group">
                                <label for="digit1">Digit 2</label>
                                <input onchange="resultCal()"  type="number" name="digit2" min="0" placeholder="Type Digit 2" class="form-control" id="digit2" />
                            </div>
                            <div class="form-group">
                                <label for="digit3">Digit 3</label>
                                <input onchange="resultCal()"  type="number" name="digit3" min="0" placeholder="Type Digit 3" class="form-control" id="digit3" />
                            </div>
                            <div class="form-group">
                                <label for="result">Result</label>
                                <input type="text" name="result" placeholder="Type Result" class="form-control" id="result" readonly />
                            </div>
                            <button type="submit" class="btn btn-primary">Add New</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function resultCal() {
            let digit1 = $('#digit1').val();
            let digit2 = $('#digit2').val();
            let digit3 = $('#digit3').val();
            if(digit1 === ""){
                digit1 = 0;
            }
            if(digit2 === ""){
                digit2 = 0;
            }
            if(digit3 === ""){
                digit3 = 0;
            }
            var id = parseInt(digit1) + parseInt(digit2) + parseInt(digit3);
            var str = String(id);
            var lastChar = str.substr(str.length - 1); // => "1"
            $("#result").val(lastChar);
        }
    </script>
@endsection

