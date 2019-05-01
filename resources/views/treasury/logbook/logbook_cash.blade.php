<div class="row mb-2" id="cashlog-scroll-to">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-vcenter">
                <span class="vcenter">Cash Log book</span>
                {{--<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bu-modal">Select add to cash</button>--}}
                <button type="button" data-toggle="collapse" data-target="#collapse-cash" aria-expanded="false" aria-controls="collapse-cash" class="btn btn-outline-secondary btn-sm float-right minimize-btn btn-collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="collapse show" id="collapse-cash">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="cashlog-table" class="table table-hover jc-table table-borderless table-striped">
                                <thead>
                                <tr>
                                    <th>Cash logbook description</th>
                                    <th>Sales date</th>
                                    <th>DS #</th>
                                    <th>Amount</th>
                                    <th>
                                        <i class="fa fa-bars"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="jc-table-body">
                                <tr>
                                    <td colspan="5">
                                        <span class="float-right">Total: <span id="cashlog-total-123" class="cashlog-total">000</span></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-12">
                            <button data-url="{{ route('trlogbookaddunits') }}" data-sales="{{ $sales_date }}" id="open-cash-modal" type="button" class="btn btn-primary float-right btn-oblong btn-blue" data-toggle="modal" data-target=".add-adj-modal">Add</button>
                        </div>
                    </div>

                    {{--<button type="button" class="btn btn-secondary float-right" id="manual-add-cash" data-toggle="modal" data-target=".add-cash-modal-sm">Add cash manually</button>--}}
                </div>
            </div>
        </div>

    </div>
</div>