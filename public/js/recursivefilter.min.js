// bikin function filter utk semua

function set_default() {
    $('#group-filter-dir').addClass('d-none');
    $('#group-filter-subdir').addClass('d-none');
    $('#group-filter-div').addClass('d-none');
    $('#group-filter-bag').addClass('d-none');

    $('#label-filter-dir').html('Direktorat');
    $('#label-filter-subdir').html('Sub Direktorat');
    $('#label-filter-div').html('Divisi');
    $('#label-filter-bag').html('Bagian');
}

function target_by_levelkantor(tipe_kantor, child, is_child = false) {
    var group = $('#'+child).data('group');
    if (tipe_kantor == 0 || is_child) {
        console.log(group);
        $('#'+group).removeClass('d-none');
        return child;

    } else if (tipe_kantor == 1) {
        $('#group-filter-div').removeClass('d-none');
        $('#label-filter-div').html('Bidang');
        return 'filter-div';
    
    } else {
        $('#group-filter-bag').removeClass('d-none');
        return 'filter-bag';
    }
}

function loadkantor(el) {
    var url                  = $(el).data('url');
    var tipe_unit_kerja_code = $(el).val();
    var target               = $(el).data('child');
    var select               = $(el).data('default');
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            tipe_unit_kerja_code: tipe_unit_kerja_code
        },
        timeout: 30000,
        beforeSend: function(xhr) {
            set_default()
            kosongkanOptionsChild(target);
            // $('#'+target).empty();
        },
        success: function(res) {
            $.each(res, (i, v) => {
                let option = $("<option></option>");
                option.val(i);
                option.text(v);
                if (i == select) {
                    option.attr('selected', 'selected');
                }
                $('#'+target).append(option);
            });
            $('#'+target).trigger('change');
        },
        error: function(x, t, m) {
            if (t === 'timeout') {
                Swal.fire({
                    icon: 'error',
                    title: 'Timeout Exceeded!',
                    text: 'Terjadi masalah dijaringan yang anda gunakan ....'
                })
            }
        }
    })
}

function loadbagian(el) {
    set_default()
    var url             = $(el).data('url');
    var unit_kerja_code = $(el).val();
    var level_kantor    = $('#tipe_unit_kerja_code_filter').val();
    var target          = target_by_levelkantor(level_kantor,$(el).data('child'));
    console.log(target,level_kantor);
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            unit_kerja_code: unit_kerja_code
        },
        timeout: 30000,
        beforeSend: function(xhr) {
            kosongkanOptionsChild(target);
            // $('#'+target).empty();
        },
        success: function(res) {
            _filter_bagian_child = res.data.child;

            $.each(res.data.parent, (i, v) => {
                let option = $("<option></option>");
                option.val(i);
                option.text(v.name);
                if (typeof v.selected != 'undefined' && v.selected == true) {
                    option.attr('selected', 'selected');
                }
                $('#'+target).append(option);
            });
            $('#'+target).trigger('change');
        },
        error: function(x, t, m) {
            if (t === 'timeout') {
                Swal.fire({
                    icon: 'error',
                    title: 'Timeout Exceeded!',
                    text: 'Terjadi masalah dijaringan yang anda gunakan ....'
                })
            }
        }
    })
}

function loadchild(el) {
    var bagian_code  = $(el).val();
    var level_kantor = $('#tipe_unit_kerja_code_filter').val();

    if (_filter_bagian_child !== '' && typeof _filter_bagian_child[bagian_code] != 'undefined') {
        var target       = target_by_levelkantor(level_kantor,$(el).data('child'), true);
        kosongkanOptionsChild(target)
        $.each(_filter_bagian_child[bagian_code], (i, v) => {
            let option = $("<option></option>");
            option.val(i);
            option.text(v.name);
            if (typeof v.selected != 'undefined' && v.selected == true) {
                option.attr('selected', 'selected');
            }
            $('#'+target).append(option);
        });
        $('#'+target).trigger('change');
    } else {
        var child = $(el).data('child');
        var group = $('#'+child).data('group');
        $('#'+group).addClass('d-none');
        kosongkanOptionsChild(child);
    }
    
}

function kosongkanOptionsChild(id) {
    var element = $('#'+id);
    element.empty();
    var childId = element.data('child');
    var groupChild = $('#'+childId).data('group');
    $('#'+groupChild).addClass('d-none');
    if (typeof childId != 'undefined') {
        kosongkanOptionsChild(childId);
    }
}