@foreach($cash_categories as $key => $c)

    <tr id="cashlog-data-{{ $c->id-100 }}" class="newly-added-item">
        <td>

            @if(strtolower($c->cash_category->description) == 'admin: ar others')
                {{ $c->description }}
                <div class="">
                    <input style="width: 30%;" class="" type="text" name="arFrom[]"> to <input style="width: 30%;" type="text" name="arTo[]">
                </div>
            @else
                <input type="hidden" name="arFrom[]"><input type="hidden" name="arTo[]">
                {{ $c->cash_category->description }}
            @endif
            <input type="hidden" name="autoids[]" value="1">
            <input type="hidden" name="cashids[]" value="{{ $c->id-100 }}">
            <input type="hidden" name="inputStatus[]" value="tre">
            <input type="hidden" name="logbookDesc[]" value="{{ $c->cash_category->description }}">
            <input type="hidden" name="hrmsCode[]" value="{{ $c->cash_category->id }}">
            <input type="hidden" name="csAmount[]" value="0">
        </td>
        <td>
            <input type="hidden" name="sales_date[]" value="{{ $sales_date->format('Y-m-d') }}">
            {{ $sales_date->format('F d, Y') }}
        </td>
        <td class="{{ (strtolower($c->cash_category->description) == 'po')?'multiple':'' }}">
            @if(strtolower($c->cash_category->description) == 'po')
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-group-button multiple-ds-amount-btn" id="validationTooltipUsernamePrepend" data-toggle="modal" data-target=".bd-example-modal-sm">+</span>
                    </div>
                    <span class="input-box form-control">
                        <ul></ul>
                    </span>
                </div>
            @else
                <input name="ds[]" id="ds-{{ $buCount }}" type="text" placeholder="Input DS #" value="" autocomplete="off" class="form-control dsclass">
            @endif
            <input type="hidden" name="status_adj[]" value="default">
        </td>
        <td>
            <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="cs_amount[]" type="text" placeholder="Input amount" autocomplete="off" class="form-control amount-change" id="cashamount-{{ $cashConCount }}" {{ ($c['liq_amount'] > 0)?'value="'.number_format($c['liq_amount'],2).'" readonly':'' }} {{ (strtolower($c->cash_category->description) == 'po')?'readonly':'' }}>
        </td>
        <td>
            @if(strtolower($login_user->businessunit->bname) == 'plaza marcela')
                <button type="button" class="btn remove-cash-selected"><i class="fa fa-times"></i></button>
            @endif
            {{--<button type="button" class="btn remove-cash-selected"><i class="fa fa-times"></i></button>--}}
        </td>
    </tr>
    @if(strtolower($c->description) != 'po')
        {{ $buCount++ }}

    @endif
    {{ $cashConCount++ }}
@endforeach