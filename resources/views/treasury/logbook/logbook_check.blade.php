<div class="col-md-6">
    <div class="card card-check">
        <div class="card-header card-vcenter">
            <span class="vcenter">Check</span>
            <button type="button" data-toggle="collapse" data-target="#collapse-check" aria-expanded="false" aria-controls="collapse-check" class="btn btn-outline-secondary btn-sm float-right minimize-btn btn-collapse"><i class="fa fa-minus"></i></button>
        </div>
        <div class="collapse show" id="collapse-check">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="ballon">
                        <div class="speech-bubble">
                            <span><i class="fa fa-exclamation-circle"></i> Click on the blue icon to view check details!</span>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th colspan="2">Controls</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($checks_base_class_final as $keys => $check_class)
                            <tr>
                                <td>
                                    {{ $check_class['check_class'] }}
                                </td>
                                <td colspan="2">
                                    <button data-url="{{ route('trlogbookcheckdetail') }}" data-class="{{ $check_class['check_class'] }}" data-sales="{{ $sales_date }}" type="button" class="btn-oblong btn-small-circle view-check-detail" data-action="all" data-toggle="modal" data-target=".check-detail-modal"><i class="fa fa-video-camera"></i></button>
                                </td>
                                <td>{{ number_format($check_class['check_class_total'],2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <input type="hidden" value="{{ $pdc_received_on_sales_date->sum('check_amount') }}" name="pdc_in">
                                <input type="hidden" value="{{ json_encode($pdc_received_on_sales_date) }}" name="pdc_details">
                                <input type="hidden" value="{{ json_encode($pdc_checks) }}" name="pdc_other_details">
                                <input type="hidden" value="{{ json_encode($datedCheck) }}" name="dc_details">
                                <input type="hidden" value="{{ json_encode($dated_checks) }}" name="dc_other_details">
                                <input type="hidden" value="{{ json_encode($due_checks) }}" name="duecheck_details">
                                <input type="hidden" value="{{ $due_check_total }}" name="due_check_in">
                                <span class="">TOTAL: <b>{{ number_format($checks_sum, 2) }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">PDC RECEIVED ON {{ $carbon_sales_date->format('F d, Y') }}:</td>
                            <td><b>{{ number_format($pdc_received_on_sales_date->sum('check_amount'), 2) }}</b></td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td colspan="3">PDC NOW:</td>--}}
                        {{--<td><b>{{ number_format($pdc_total, 2) }}</b></td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td colspan="3">DATED CHECK ON {{ $carbon_sales_date->format('F d, Y') }}:</td>
                            <td><b>{{ number_format($due_check_total, 2) }}</b></td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td colspan="3">DATED CHECK NOW</td>--}}
                        {{--<td><b>{{ number_format($datedCheckNow->sum('check_amount'), 2) }}</b></td>--}}
                        {{--</tr>--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>