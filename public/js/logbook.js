$(document).ready(function () {

    $('.ballon').fadeIn(1600);

    // var element = $('#collapse-check tbody').find('td:eq(1) button');
    // var rect = element.offset();
    // var rect2 = element.position();
    // console.log(rect.left);
    // console.log(rect2.left);
    // coor = rect.left/rect2.left;
    // $('.speech-bubble').css('left',coor);



    setTimeout(function(){
        toastr.info("If you accidentally deleted a row, try reloading the page again!", "Info", {
            "debug": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "preventDuplicates": true,
            "showDuration": "30",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });
    }, 5000);

    setTimeout(function(){
        $('.ballon').fadeOut(1600);
    }, 10000);

    $('.view-check-detail').on('click', function (e) {
        var checkClass = $(this).attr('data-class'),
            dateR = $(this).attr('data-sales'),
            url = $(this).attr('data-url'),
            action = $(this).attr('data-action');

        $('.check-viewing-btn').attr('data-class', checkClass);

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({
            method: 'POST',
            url: url,
            data: {
                checkClass: checkClass,
                dateR: dateR,
                action: action
            },
            success: function (res) {
                // console.log(res);
                $('.check-title').text(res.title);
                var data  = res.tabledata;
                table = $('#check-detail-table').DataTable({
                    destroy:true,
                    data:data,
                    ordering:false,
                    "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                    "pageLength": 5,
                    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
                    {
                        if ( aData[9] == "POST DATED" )
                        {
                            $('td', nRow).css({
                                'color': 'red'
                            });
                        }
                        else
                        {
                            $('td', nRow).css('color', 'black');
                        }
                    }
                });

            },
            error: function (error) {
                alert('server error');
            }
        });

    });

    $('#cashlog-table').on('click', '.view-cashier-list', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            method: 'GET',
            url: url,
            success: function (res) {
                // console.log(res);
                $('.total-denomination').text(res.total);
                table = $('#cashier-list-table').DataTable({
                    destroy:true,
                    data:res.details,
                    ordering:false,
                    "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                    "pageLength": 5,
                });
                // $('#cashier-list-table tbody').html('').append(res);
            }
        });

    });

});

$('#open-cash-modal').on('click', function (e) {

    var id = $('select[name="bankaccounts"]').val(),
        url= $(this).attr('data-url'),
        sales_date = $(this).attr('data-sales'),
        pdc = $('input[name="pdc_in"]').val(),
        dueChecks = $('input[name="due_check_in"]').val();

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $.ajax({
        method: 'POST',
        url: url,
        data: {
            id: id,
            sales_date: sales_date,
            pdcTotal: pdc,
            dueChecks: dueChecks
        },
        success: function (res) {
            var busb = $('select[name="bu_sb"]');
            busb.find('.new-added-option').remove();
            busb.append(res.view);
        }
    });

});

$('select[name="bankaccounts"]').on('change', function(e) {

    $('.loader-wrapper').show();

    var id = $(this).val(),
        url= $(this).attr('data-url'),
        sales_date = $(this).attr('data-sales'),
        pdc = $('input[name="pdc_in"]').val(),
        dueChecks = $('input[name="due_check_in"]').val();

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $.ajax({
        method: 'POST',
        url: url,
        data: {
            id:id,
            sales_date:sales_date,
            pdcTotal:pdc,
            dueChecks:dueChecks
        },
        success: function (res) {
            $('#cashlog-table').find('.newly-added-item').remove();

            $('#cashlog-table').find('tbody').prepend(res.view);

            $('#cashlog-total-123').text(res.cashtotal);

            $(".amount-change").inputmask();

            $('.total-no-sm').text(res.total_no_sm);

            $('.loader-wrapper').hide();

            $('html,body').animate({
                    scrollTop: $("#cashlog-scroll-to").offset().top},
                'slow');

        },
        error: function (res) {
            console.log(res);
            $('.loader-wrapper').hide();
        }
    });


});

$('#cashlog-table').on('keyup', '#ds-0', function(e) {
    var val = $(this).val();
    $('#cashlog-table').find('.dsclass').val(val);
});

$('[name="status_adj"]').on('change', function (e) {
    if ($(this).val() == 'check-to-cash') {
        $('.bu-sb').hide();
    } else {
        $('.bu-sb').show();
    }
});

var cashlogTable =  $('#cashlog-table');

cashlogTable.on('click', '.remove-cash-selected', function (e) {
    e.preventDefault();

    var selected_bu_amount = $(this).closest('tr').find('.amount-change').val();
    var cashTotal = $('#cashlog-total-123').text();

    filtered_amount = String(selected_bu_amount).replace(/\,/g,"");
    filtered_cash_total = cashTotal.replace(/\,/g,"");
    if(!filtered_amount) {
        filtered_amount = 0;
    }
    newCashTotal = parseFloat(filtered_cash_total) - parseFloat(filtered_amount);
    $('#cashlog-total-123').text(newCashTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,','));
    $(this).closest('tr').remove();
});

$('#add-manual-cash-adj').on('submit', function (e) {
    var formdata = $(this).serialize(),
        url = $(this).attr('action');

    $.ajax({
        method: 'POST',
        url: url,
        data: formdata,
        success: function (res) {
//                    console.log(res);
            $('.manual-cash-adj-'+res.id).remove();
            $('#cashlog-table').find('tbody tr:last').before(res.view);

            var totalCash = 0;

            $('input[name^="cs_amount"]').each( function (e) {
                if(this.value) {
                    string_value = this.value;
                    filtered_value = string_value.replace(/\,/g,"");
                    totalCash += parseFloat(filtered_value);
                }
            });

            $('#cashlog-table').find('.cashlog-total').text(String(totalCash.toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g,','));
        }
    });

});

$('#cash-pull-out-form').on('submit', function (e) {
    e.preventDefault();
    var formdata = $(this).serialize(),
        url = $(this).attr('action'),
        btnSubmit = $(this).find('button[type="submit"]');

    btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
        'Loading...').prop('disabled', true);

    $.ajax({
        method: 'POST',
        url: url,
        data: formdata,
        success: function (res) {

            $('#cpo-table').find('tr.cpo-data-class-'+res.id).remove();
            $('#cpo-table').prepend(res.view);

            //supermarket
            var liq_amount = $('#cashlog-table').find('.liq-total').text();
            var cpo_amount = $('#cashlog-table').find('.cpo-total').text();
            var sm_amount = $('#cashlog-table').find('.sm-total').text();

            var liq_less_check = $('#cashlog-table').find('#liq-less-check').val();

            var total_no_sm = $('.total-no-sm').text();

            var cpototal = res.cpototaltext;

            if(liq_less_check) {

                var ajax_calculated_total = liq_less_check - res.cpototal;

//                        var cashtotal = parseFloat(total_no_sm)+parseFloat(ajax_calculated_total);

                $('#cashlog-table').find('.sm-total').val(ajax_calculated_total);

                $('#cashlog-table').find('.cpo_total_in').val(res.cpototal);
                $('#cashlog-table').find('.cpo-total').text(cpototal);


                var totalCash = 0;

                $('input[name^="cs_amount"]').each( function (e) {
                    if(this.value) {
                        string_value = this.value;
                        filtered_value = string_value.replace(/\,/g,"");
                        totalCash += parseFloat(filtered_value);
                    }
                });

                $('#cashlog-table').find('.cashlog-total').text(String(totalCash.toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g,','));

            }
            $('#cpo-table').find('.cpo-total').text(cpototal);
            $('.add-cash-pull-out-modal').modal('hide');

            btnSubmit.text('Add').prop('disabled', false);
        },
        error: function (error) {
            btnSubmit.text('Add').prop('disabled', false);
        }
    });
});

$('#checkallbus').click(function() {
    var checked = $(this).prop('checked');
    $('#busitem_cb').find('input:checkbox').prop('checked', checked);
});

$('#add_bu_form').submit(function (e) {
    e.preventDefault();
    var url = $(this).attr('action'),
        method = $(this).attr('method'),
        formdata = $(this).serialize(),
        pdc = $('input[name="pdc_in"]').val(),
        dueChecks = $('input[name="due_check_in"]').val();

    $.ajax({
        method: method,
        url: url,
        data: formdata+'&pdcTotal='+pdc+'&dueChecks='+dueChecks,
        success: function (res) {

            $('#cashlog-table').find('.newly-added-item').remove();

            $('#cashlog-table').find('tbody').prepend(res.view);

            $('#cashlog-total-123').text(res.cashtotal);

            $(".amount-change").inputmask();

            $('.total-no-sm').text(res.total_no_sm);
        },
        error: function (error) {

        }
    });

});

$('#add_sm_ds').on('click', function (e) {

    // alert('sdfsdf');

    var ds_number = $('#ds_number').val(),
        amount = $('#multiple-amount').val(),
        trId = $('input[name="trId"]').val();
    //
    if(ds_number.length === 0) {
        $('#ds_number').addClass('is-invalid').removeClass('is-valid');
        return 0;
    }

    $('#cashlog-table > tbody').find('tr.newly-added-item').each( function (index, item) {
        var dsIndex = $(this).find('td:eq(2)').hasClass('multiple');
        if (dsIndex == true) {
            $('#cashlog-table').find('.input-box ul').append('<li class="input-box-item">' +
                '<span class="box_remove" role="presentation">×</span> DS: '+
                ds_number + ' AM: <span class="multiple-amt">' + amount + '</span>'+
                '<input name="ds['+index+'][0][]" type="hidden" value="'+ds_number+'">'+
                '<input name="ds['+index+'][1][]" type="hidden" value="'+amount+'">'+
                '</li>');

            cs_amount = $('input[name="ds['+index+'][1][]"]').map(function () {
                return $(this).val();
            }).get();
        }
    });

    //




    var total_amount = 0;
    for (var i = 0; i < cs_amount.length; i++) {
        total_amount += parseFloat(cs_amount[i]);
    }

    $('#'+trId).find('.amount-change').val(total_amount).trigger('keyup');

    if ( $('.input-box ul').children().length > 0 ) {
        $('.input-box').addClass('is-valid').removeClass('is-invalid');
    }

    $('.bd-example-modal-sm').modal('hide')
});

$('#cashlog-table').on('click', '.multiple-ds-amount-btn',function (e) {

    var trId = $(this).closest('tr').attr('id');

    $('input[name="trId"]').val(trId);
});

$('#cashlog-table').on('click', '.box_remove',function (e) {
    var inputVal = $(this).closest('li').find('.multiple-amt').text();

    totalAmount = $(this).closest('tr').find('.amount-change').val();

    totalAmount = totalAmount.replace(/\,/g,'');

    console.log(totalAmount);

    newAmount = parseFloat(totalAmount)-parseFloat(inputVal);

    $(this).closest('tr').find('.amount-change').val((newAmount<0)?'0':newAmount).trigger('keyup');


    $(this).closest('.input-box-item').remove();
    ($('.input-box ul').children().length > 0 )?$('.input-box').addClass('is-valid').removeClass('is-invalid'):$('.input-box').addClass('is-invalid').removeClass('is-valid');
});

$('#add-cash-22321').on('submit', function (e) {
    e.preventDefault();
    var formdata = $(this).serialize(),
        url = $(this).attr('action');

    $.ajax({
        method: 'PUT',
        url: url,
        data: formdata,
        success: function (res) {
            jQuery('#modal-alert').show();
            jQuery('#modal-alert ul').text('');
            jQuery('#modal-alert').addClass('alert-success').removeClass('alert-danger');
            jQuery('#modal-alert ul').append('<li>'+res.message+'</li>');

            if($('#cashlog-table').find('tbody').has('#cashlog-data-'+res.id).length) {
                $('#cashlog-table tbody').find('tr#cashlog-data-'+res.id+' td:last').text(res.amount);
                $('#cashlog-table tbody').find('tr#cashlog-data-'+res.id+' td:nth-child(3)').text(res.ds);
                $('#cashlog-table tbody').find('tr#cashlog-data-'+res.id+' td:first span.ardata').text(res.ardata);
            } else {
//                        $('<tr id="cashlog-data-'+res.view.id+'">' +
//                                '<td>'+res.view.description+'</td>'+
//                                '<td>'+res.view.sales_date+'</td>'+
//                                '<td>'+res.view.ds_number+'</td>'+
//                                '<td>'+String(res.view.amount_edited).replace(/\B(?=(\d{3})+(?!\d))/g,',')+'</td>'+
//                            '</tr>').insertAfter(".cashlogslist2:last");
                $(res.view).insertAfter(".cashlogslist2:last");
            }

            $('#arFrom').val('');
            $('#arTo').val('');
            $('#ds').val('');
            $('#cashamount').val('');

            $('#cashlog-total-123').text(res.total);

        },
        error: function (error) {
            var errordata = JSON.parse(error.responseText);
            jQuery('#modal-alert ul').text('');
            jQuery.each(errordata.errors, function(key, value){
                jQuery('#modal-alert').show();
                jQuery('#modal-alert').removeClass('alert-success').addClass('alert-danger');
                jQuery('#modal-alert ul').append('<li>'+value+'</li>');
            });
        }
    });
});

$('.add-cash-modal-sm').on('show.bs.modal', function (e) {
    jQuery('#modal-alert').hide();
    jQuery('#ds').val('');
    jQuery('#cashamount').val('');
    $('#arFrom').val('');
    $('#arTo').val('');
});

$('select[name="department"]').on('change', function (e) {
    e.preventDefault();
    $('#arFrom').val('');
    $('#arTo').val('');
    ($(this).val() == 21)?$('.has-ar').show():$('.has-ar').hide();
});

$('#logbook-form-submit').submit(function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var formdata = $(this).serialize();

    var submitBtn = $(this).find('button[type="submit"]');

    submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
        'Loading...').prop('disabled', true);

    if ( $('.input-box ul').children().length === 0 ) {
        $('.input-box').addClass('is-invalid').removeClass('is-valid');
    }

    $.ajax({
        method: 'PUT',
        url: url,
        data: formdata,
        success: function (res) {

            $('.alert').remove();

            if (res.type == 'error') {
                $('.last-row').after('<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                    '                <strong>Error!</strong> '+res.message+'\n' +
                    '                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                    '                    <span aria-hidden="true">&times;</span>\n' +
                    '                </button>\n' +
                    '            </div>');
                toastr.error(res.message, "Error", {
                    "debug": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-bottom-left",
                    "preventDuplicates": true,
                    "showDuration": "30",
                    "hideDuration": "1000",
                    "timeOut": "10000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
                if (res.message === 'Looks like sales date is greater than or equal to deposit date not valid!') {
                    $('#datedeposit').addClass('is-invalid').removeClass('is-valid');
                    $('html,body').animate({
                            scrollTop: $("#collapse-top-card").offset().top},
                        'slow');
                }
                submitBtn.prop('disabled', false);
                return;
            }

            $('.last-row').after('<div class="alert alert-success alert-dismissible fade show" role="alert">\n' +
                '                <strong>Success!</strong> '+res.message+'\n' +
                '                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '                    <span aria-hidden="true">&times;</span>\n' +
                '                </button>\n' +
                '            </div>');

            $('input').addClass('is-valid').removeClass('is-invalid').val('');
            $('#cashlog-table').find('tbody .newly-added-item').remove();
            $('#cashlog-total-123').text('0');
            $('select[name="currency"]').val('0');
            $('select[name="bankaccounts"]').val('0');

            toastr.success("Successfully saved!", "Success", {
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-bottom-left",
                "preventDuplicates": true,
                "showDuration": "30",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

            submitBtn.text('Submit data fields').prop('disabled', false);

        },
        error: function (error) {

            var errordata = JSON.parse(error.responseText);

            console.log(errordata);

            toastr.error(errordata.message, "Error", {
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-bottom-left",
                "preventDuplicates": true,
                "showDuration": "30",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

            $('html,body').animate({
                    scrollTop: $("#collapse-top-card").offset().top},
                'slow');

            $('.invalid-feedback').remove();
            jQuery.each(errordata.errors, function(key, value){
                var errorstr = value[0];

                errortrim = errorstr.replace(/\sfield.*/g,"");
                errortrim = errortrim.replace(/The\s/g,"");
                errortrim = errortrim.replace(/selected\s/g, "");
                errortrim = errortrim.replace(/\sis\sinvalid.*/g, "");

                errortrim = errortrim.trim();

                amount = errorstr.match(/cs_amount.\d*/gi);
                if(amount) {
                    amount = amount.toString();
                    amount = amount.replace(/[^a-zA-Z0-9]/g,"");
                    amount = amount.replace(/[a-zA-Z]/g, "");
                    amount = amount.trim();
                }


                console.log(amount);

                var errornumbers = errorstr.match(/\d+/i);

                if (errornumbers) {
                    $('#ds-'+errornumbers+',#cashamount-'+amount).addClass('is-invalid').removeClass('is-valid');
                } else {
                    $('[name="'+errortrim+'"]').addClass('is-invalid').removeClass('is-valid').after('<span class="invalid-feedback" role="alert">\n' +
                        '                                                <strong>'+errorstr+'</strong>\n' +
                        '                                            </span>');
                }

            });

            submitBtn.text('Submit data fields').prop('disabled', false);

        }
    });
});

//        var url = new URL(window.location.href);
var url = window.location.href;
//        var date = url.searchParams.get("date");
var date = url.match(/\d{4}-\d{2}-\d{2}/i);
var splitdate = date[0].split('-');

$('#datedeposit').datetimepicker({
    pickTime: false,
    format: "YYYY-MM-DD",
    useCurrent: false,
    minDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2]),
    startDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2])
});

$('#manual_sales_date').datetimepicker({
    pickTime: false,
    format: "YYYY-MM-DD",
    useCurrent: false,
    maxDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2]),
    startDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2])
});

$('body').on('keyup change blur', 'input,select', function (e) {
    var inputval = $(this).val();
    inputval = inputval.trim();
    if (inputval.length > 0 && inputval != 0) {
        $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
        $(this).addClass('is-invalid').removeClass('is-valid');
    }
});

$('body').on('keyup blur', 'input[name="cs_amount[]"]', function (e) {
    var total = 0;
    var amount = $("input[name='cs_amount[]']")
        .map(function(){return $(this).val();}).get();

    var i;
    for (i = 0; i < amount.length; i++) {

        if (amount[i].length>0) {
            amount[i] = amount[i].replace(/\,/g,'');
            if (!isNaN(amount[i])) {
                total = parseFloat(total) + parseFloat(amount[i]);
            }
        }
    }

    $('#cashlog-total-123').text(String(total.toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g,','));

});

$('[data-toggle="collapse"]').on('click', function (e) {
    $(this).toggleClass( "active" );
    if ($(this).hasClass("active")) {
        $(this).find('i').addClass('fa-plus').removeClass('fa-minus');
    } else {
        $(this).find('i').addClass('fa-minus').removeClass('fa-plus');
    }
});

$('#cashlog-table').on('click', '[data-toggle="collapse"]', function (e) {
    $(this).toggleClass( "active" );
    if ($(this).hasClass("active")) {
        $(this).find('i').addClass('fa-eye').removeClass('fa-eye-slash');
    } else {
        $(this).find('i').addClass('fa-eye-slash').removeClass('fa-eye');
    }
});