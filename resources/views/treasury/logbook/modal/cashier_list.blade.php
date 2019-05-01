<div class="modal fade cashier-list-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="add-manual-cash-adj" method="POST" action="{{ route('tr_add_manual_cash_adj') }}" onsubmit="event.preventDefault();">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cashiers list total: <span class="total-denomination"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="cashier-list-table" class="table table-hover dt-responsive nowrap" style="width: 100% !important;">
                            <thead>
                            <tr>
                                <th>Cashier id</th>
                                <th>Cashier name</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-oblong btn-orange" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>