<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formModal">Manage Points</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="GET" id="deposit-withdraw-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" placeholder="Name" name="name" id="name" value="{{ $user->name }}" readonly />
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" placeholder="Phone" name="phone" id="phone" value="{{ $user->phone }}" readonly />
                </div>
                <div class="form-group">
                    <label for="points">Points</label>
                    <input type="text" class="form-control" placeholder="Points" id="points" value="{{ $user->points }}" readonly />
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" class="form-control" placeholder="Amount" name="points" id="amount" value="" />
                </div>
                <button type="submit" class="btn {{ ($type == 'deposit') ? 'btn-primary' : 'btn-danger' }} m-t-15 waves-effect update-points">
                    @if($type == 'deposit')
                        Deposit Point
                    @else
                        Withdraw Point
                    @endif
                </button>
            </form>
        </div>
    </div>
</div>
