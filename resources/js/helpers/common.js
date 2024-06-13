// Page Setup
function initPage() {
    if ($('select.select2').length) {
        $.each($('select.select2'), function () {
            var opt = {};

            if (typeof $(this).data('search') != 'undefined' && $(this).data('search') == false) {
                opt = $.extend(true, opt, { minimumResultsForSearch: -1 });
            }
            $(this).select2(opt);
        });
    }

    $('[rel="tooltip"]').tooltip();

    if ($.fn.datepicker) {
        $("#startDate").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            orientation: 'bottom',
            endDate: new Date()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', minDate);
        });

        $("#endDate").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            orientation: 'bottom',
            endDate: new Date()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', minDate);
        });
    }

}

function cbCustomInput(e) {
    var textOn = $(e).data('text-on');
    var textOff = $(e).data('text-off');
    var name = $(e).attr('id');
    if (typeof name == 'undefined' || name == '') {
        name = $(e).attr('name');
    }

    var target = $('label[for=' + name + ']');
    if ($(e).is(":checked")) {
        target.html(textOn);
    } else {
        target.html(textOff);
    }
}

//REMOTE MODAL
function initModalAjax(selector, parent) {

    var selector_triger = typeof selector !== 'undefined' ? selector : '[data-bs-toggle="modal"]';
    var element = $(selector_triger);

    if (typeof parent != 'undefined') {
        var element = $(selector_triger, parent);
    }

    element.on('click', function (e) {
        /* Parameters */
        var url = $(this).attr('href');
        let containerTarget = $(this).attr('data-bs-target');
        let form = $(this).attr('data-form');
        let data = $(form).serialize();

        if (url.indexOf('#') == 0) {
            $(containerTarget).modal();
        } else {
            /* XHR */
            var loading = "<i class='bx bx-loader bx-spin bx-sm'></i>";

            $(containerTarget).modal();
            // $('.modal-content', $(containerTarget)).html(loading).load(url, data, function () { });
            $.ajax({
                type: 'GET',
                url: url,
                data: data,
                dataType: 'html',
                beforeSend: function(){
                    $('.modal-content', $(containerTarget)).html('<div class="modal-body"><div class="d-flex justify-content-center">'+loading+'</div></div>').waitMe("show");
                },
                complete: function(){
                    $('.modal-content', $(containerTarget)).waitMe("hide");
                },
                success: function(r){
                    $('.modal-content', $(containerTarget)).html(r);
                }
            })

        }
        return false;
    });
}

// DATA TABLES
function initDatatableAction(table_id, callback) {
    $('.btn-delete', table_id).on('click', function () {
        $(this).myAjax({
            success: function (data) {
                callback();
            }
        }).delete();

        return false;
    });

    $('.btn-restore', table_id).on('click', function () {
        $(this).myAjax({
            type: 'PUT',
            success: function (data) {
                callback();
            }
        }).restore();

        return false;
    });

}

function initDatatableTools(table_id, oTable) {
    var header_id = $('[data-table="#' + $(table_id).attr('id') + '"]');
    var $form_filter = $(table_id).attr('data-table-filter');

    $($form_filter).on('submit', function (e) {
        e.preventDefault();
        oTable.reload();
    });

    $('.filter-select', $($form_filter)).on('change', function (event) {
        event.preventDefault();

        var $auto_filter = $(table_id).attr('data-auto-filter');
        if ($auto_filter == 'true') {
            oTable.reload();
        }
    });

    $('a[href="#btn-checked-all"], a[href="#btn-unchecked-all"]', table_id).on('click', function () {
        var id = $(this).attr('href');
        if (id == '#btn-checked-all') {
            $('.dt-checkbox', table_id).prop('checked', true);
        } else {
            $('.dt-checkbox', table_id).prop('checked', false);
        }
        return false;
    });

    $('.btn-delete-selected', table_id).on('click', function () {
        var id = [];

        $.each($('.dt-checkbox', table_id), function () {
            if ($(this).is(':checked')) {
                id.push($(this).val());
            }
        });

        if (id.length > 0) {
            $(this).myAjax({
                data: {
                    _id: id
                },
                success: function (data) {
                    oTable.reload();
                }
            }).delete({ confirm: { text: __('helpers.common.multiple_delete', { total: id.length }) } });
        } else {
            command: toastr["warning"](__('helpers.common.nf_cb_selected'));
        }

        return false;
    });

    $('.auto_filter', header_id).on('switchChange.bootstrapSwitch', function (event, state) {
        $('table#main-table').attr('data-auto-filter', $(this).is(':checked') ? 'true' : 'false');
    });

    $('.reload-table', header_id).on('click', function () {
        oTable.reload();
        return false;
    });

    $('.reset-filter', header_id).on('click', function () {
        oTable.filterReset();
        return false;
    });
}

function dd_cascade(target, url, data, selected) {
    $(target).html('<option value="">Loading...</option>');

    $.get(url, data, function (out) {
        var options = '';
        $.each(out.data, function (idx, val) {
            options += '<option value="' + idx + '">' + val + '</option>';
        });

        var checked = typeof selected != 'undefined' ? selected : '';
        $(target).html(options);
        $(target).val(checked);
    }, 'json');
}


function do_export(form, url) {
    var data = $(form).serialize();

    window.open(url + '?' + data, '_blank');
}
