<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formModal">Update Market</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form class="">
                <div class="form-group">
                    <label>Market Name</label>
                    <input type="text" class="form-control" placeholder="Name" name="market" id="market_name" value="{{ $market->name }}">
                    <input type="hidden" name="id" id="market_id" value="{{ $market->id }}">
                </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect update-market">Update</button>
            </form>
        </div>
    </div>
</div>
