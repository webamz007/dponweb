<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formModal">Add New Market</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="add-market">
                <div class="form-group">
                    <label for="market_name">Market Name</label>
                    <input type="text" class="form-control" name="market_name" id="market_name">
                </div>
                <div class="form-group">
                    <label for="open_time">Open End Time</label>
                    <input type="text" class="form-control timepicker" name="open_time" id="open_time">
                </div>
                <div class="form-group">
                    <label for="close_time">Close End Time</label>
                    <input type="text" class="form-control timepicker" name="close_time" id="close_time">
                </div>
                <div class="form-group">
                    <label for="open_result">Open Result Time</label>
                    <input type="text" class="form-control timepicker" name="open_result" id="open_result">
                </div>
                <div class="form-group">
                    <label for="close_result">Close Result Time</label>
                    <input type="text" class="form-control timepicker" name="close_result" id="close_result">
                </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect add-market">Add New</button>
            </form>
        </div>
    </div>
</div>
