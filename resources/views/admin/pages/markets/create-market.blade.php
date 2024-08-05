<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formModal">Add New Market</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="starline-form">
                <div class="form-group">
                    <label for="market_name">Market Name</label>
                    <input type="text" class="form-control" name="market_name" id="market_name" placeholder="Type Name">
                    <input type="hidden" name="market_type" value="{{ $type }}">
                </div>
                <div class="form-group">
                    @php
                        $label = '';
                        ($type == 'starline') ? $label = 'Open' : $label = 'Bid';
                    @endphp
                    <label for="open_time">{{ $label }} End Time</label>
                    <input type="text" class="form-control timepicker" name="open_time" id="open_time">
                </div>
                <div class="form-group">
                    <label for="open_result">{{ $label }} Result Time</label>
                    <input type="text" class="form-control timepicker" name="open_result" id="open_result">
                </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect add-starline-market">Add {{ ucfirst($type) }} Market</button>
            </form>
        </div>
    </div>
</div>
