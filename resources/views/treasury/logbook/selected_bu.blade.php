@foreach($cashCon as $key => $c)
    <tr id="cashlog-data-{{$c['id']}}" class="newly-added-item">
        <td>
            <input type="hidden" name="arFrom[]"><input type="hidden" name="arTo[]">
            @if($c['liq_amount'] > 0)
                <a href="{{ route('tr.logbook.cashier.list', ['code' => $c['hrmscode'], 'date' => $sales_date->format('Y-m-d')]) }}" class="view-cashier-list" data-toggle="modal" data-target=".cashier-list-modal">{{ $c['bu'] }}</a>
            @else
                {{ $c['bu'] }}
            @endif
            {{--@endif--}}
            <input type="hidden" name="autoids[]" value="1">
            {{--<input type="hidden" name="autoamounts[]" value="{{ $c['liq_amount'] }}">--}}
            <input type="hidden" name="cashids[]" value="{{ $c['cash_id'] }}">
            <input type="hidden" name="inputStatus[]" value="{{ $c['input_status'] }}">
            <input type="hidden" name="logbookDesc[]" value="{{ $c['bu'] }}">
            <input type="hidden" name="hrmsCode[]" value="{{ json_encode($c['hrmscode'], JSON_FORCE_OBJECT) }}">
            <input type="hidden" name="csAmount[]" value="{{ json_encode($c['total'], JSON_FORCE_OBJECT) }}">
        </td>
        <td>
            <input type="hidden" name="sales_date[]" value="{{ $sales_date->format('Y-m-d') }}">
            {{ $sales_date->format('F d, Y') }}
        </td>
        <td>
            @if($c['bu'] == 'SUPERMARKET')
                @if(strtolower($login_user_bu) == 'asc: main')
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-group-button" id="validationTooltipUsernamePrepend" data-toggle="modal" data-target=".bd-example-modal-sm">+</span>
                        </div>
                        <span class="input-box form-control">
                            <ul></ul>
                        </span>
                    </div>
                @else
                    <input name="ds[]" id="ds-{{ $c['id'] }}" type="text" placeholder="Input DS #" value="" autocomplete="off" class="form-control dsclass">
                @endif
            @else
                @if(strtolower($login_user->businessunit->bname) == 'island city mall')
                    <input name="ds[]" id="ds-{{ $c['id'] }}" type="text" placeholder="Input DS #" value="" autocomplete="off" class="form-control {{ (strtolower($c['bu'])=='fixrite' || strtolower($c['bu'])=='medicine plus')?'':'dsclass' }}">
                @else
                    <input name="ds[]" id="ds-{{ $c['id'] }}" type="text" placeholder="Input DS #" value="" autocomplete="off" class="form-control">
                @endif

            @endif
            <input type="hidden" name="status_adj[]" value="default">
        </td>
        <td>


            @if(strtolower($c['bu']) == 'supermarket')
{{--                @if($login_user->businessunit->bname == 'PLAZA MARCELA')--}}

                {{--@else--}}
                    <div class="collapse" id="collapse-sm-details">
                        <table class="table table-hover table-borderless">
                            <thead>
                            <tr>
                                <th>Desc</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    AMOUNT INPUTTED BY LIQUIDATION CLERK
                                </td>
                                <td>
                                    <input type="hidden" name="sm_id" value="{{ $c['id'] }}">
                                    <input type="hidden" name="inputed_liq_amount" value="{{ $c['liq_amount'] }}">
                                    <span class="liq-total">{{ number_format($c['liq_amount'],2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">LESS:</th>
                            </tr>
                            <tr>
                                <td>
                                    PDC:
                                </td>
                                <td>
                                    <input type="hidden" name="pdcTotal" value="{{ $pdcTotal }}">
                                    {{ number_format($pdcTotal,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    DATED CHECKS:
                                </td>
                                <td>
                                    <input type="hidden" name="due_checks" value="{{ $dueChecks }}">
                                    {{ number_format($dueChecks,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    CASH PULL OUT:
                                </td>
                                <td>
                                    <input name="cpo_total_in" class="cpo_total_in" type="hidden" value="{{ $cpo }}">
                                    <span class="cpo-total">{{ number_format($cpo,2) }}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--<p> </p>--}}
                    {{--<p>LIST OF AMOUNT DEDUCTED FROM:</p>--}}
                    <input id="liq-less-check" type="hidden" value="{{ $liq_less_checks_total }}">
                    <div class="input-group">
                        <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="cs_amount[]" type="text" placeholder="Input amount" autocomplete="off" class="form-control amount-change sm-total" id="cashamount-{{ $c['id'] }}" {{ ($c['less_amount'] > 0 || $c['less_amount'] < 0)?'value="'.number_format($c['less_amount'],2).'" readonly':'' }}>
                        <div class="input-group-prepend">
                            <button type="button" data-toggle="collapse" data-target="#collapse-sm-details" aria-expanded="false" aria-controls="collapse-sm-details" class="btn btn-outline-primary"><i class="fa fa-eye-slash"></i></button>
                        </div>
                    </div>
                {{--@endif--}}
            @else
                <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="cs_amount[]" type="text" placeholder="Input amount" autocomplete="off" class="form-control amount-change" id="cashamount-{{ $c['id'] }}" {{ ($c['liq_amount'] > 0)?'value="'.number_format($c['liq_amount'],2).'" readonly':'' }}>
            @endif

        </td>
        <td>
            {{--<button type="button" class="btn remove-cash-selected"><i class="fa fa-times"></i></button>--}}
        </td>
    </tr>
@endforeach