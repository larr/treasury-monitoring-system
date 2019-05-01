<div class="modal fade check-detail-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="add-manual-cash-adj" method="POST" action="{{ route('tr_add_manual_cash_adj') }}" onsubmit="event.preventDefault();">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Check details: <span class="check-title">All check received on {{ $carbon_sales_date->format('F d, Y') }}</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button data-class="" data-sales="{{ $sales_date }}" data-action="all" data-url="{{ route('trlogbookcheckdetail') }}" type="button" class="btn btn-primary view-check-detail check-viewing-btn">All checks</button>
                        <button data-class="" data-sales="{{ $sales_date }}" data-action="dated" data-url="{{ route('trlogbookcheckdetail') }}" type="button" class="btn btn-primary view-check-detail check-viewing-btn">Dated checks</button>
                        <button data-class="" data-sales="{{ $sales_date }}" data-action="pdc" data-url="{{ route('trlogbookcheckdetail') }}" type="button" class="btn btn-danger view-check-detail check-viewing-btn">Post dated checks</button>
                        <button data-class="" data-sales="{{ $sales_date }}" data-action="due" data-url="{{ route('trlogbookcheckdetail') }}" type="button" class="btn btn-danger view-check-detail check-viewing-btn">Post dated due checks</button>
                    </div>
                    <div class="table-responsive">
                    <table id="check-detail-table" class="table table-hover dt-responsive nowrap" style="width: 100% !important;">
                        <thead>
                        <tr>
                            <th>Check #</th>
                            <th>Check class</th>
                            <th>Customer name</th>
                            <th>Bank account name</th>
                            <th>Bank account number</th>
                            <th>Check category</th>
                            <th>Check date</th>
                            <th>Check amount</th>
                            <th>Department</th>
                            <th>Check Type</th>
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