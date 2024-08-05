<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formModal">Update Market</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="update-game-settings">
                <div class="form-group">
                    <label for="status">Market Status</label>
                    <select name="status" id="status" class="form-control">
                        <option {{ ($market->status == 'true') ? 'selected' : '' }} value="true">Open</option>
                        <option {{ ($market->status == 'false') ? 'selected' : '' }} value="false">Close</option>
                    </select>
                    <input type="hidden" name="day" value="{{ $market->day }}">
                    <input type="hidden" name="id" value="{{ $market->market_id }}">
                </div>
                <div class="form-group">
                    <label for="week">Update For Whole Week</label>
                    <select name="week" id="week" class="form-control">
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                    </select>
                </div>
                @php
                    $label = '';
                    ($type == 'starline') ? $label = 'Open' : $label = 'Bid';
                @endphp
                <div class="form-group">
                    <label for="open_time">{{ $label }} End Time</label>
                    <input type="text" class="form-control timepicker" name="open_time" id="open_time" value="{{ \Carbon\Carbon::parse($market->oet)->format('h:i A') }}">
                </div>
                <div class="form-group">
                    <label for="open_result">{{ $label }} Result Time</label>
                    <input type="text" class="form-control timepicker" name="open_result" id="open_result" value="{{ \Carbon\Carbon::parse($market->ort)->format('h:i A') }}">
                </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect update-game-settings">Update Market</button>
            </form>
        </div>
    </div>
</div>
