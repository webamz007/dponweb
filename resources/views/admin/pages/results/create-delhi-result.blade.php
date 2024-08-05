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
                        <form action="{{ route('result.delhi.store') }}" method="GET">
                            @csrf
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" id="date" class="form-control datepicker" required />
                            </div>
                            <div class="form-group">
                                <label for="market">Select the market</label>
                                <select name="market" class="form-control select2" id="market" required>
                                    @foreach($markets as $market)
                                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="digit1">Left</label>
                                <input onchange="resultCal()" type="number" name="digit1" min="0" max="9" placeholder="Type Digit 1" class="form-control" id="digit1" oninput="restrictInput(this, 9);resultCal();" />
                            </div>
                            <div class="form-group">
                                <label for="digit2">Right</label>
                                <input onchange="resultCal()"  type="number" name="digit2" min="0" max="9" placeholder="Type Digit 2" class="form-control" id="digit2" oninput="restrictInput(this, 9);resultCal();" />
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
            var id = digit1 + digit2;
            var str = String(id);
            $("#result").val(str);
        }
        function restrictInput(inputElement, max) {
            if (parseInt(inputElement.value) > max) {
                inputElement.value = max;
            }
        }
    </script>
@endsection

