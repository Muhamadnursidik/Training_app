<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('Konfigurasi') }}</h6>
        <table id="table-configs" class="table">
            <thead class="bg-light">
                <tr>
                    <th scope="col">{{ __('Key') }}</th>
                    <th scope="col">{{ __('Label') }}</th>
                    <th scope="col">{{ __('Tipe') }}</th>
                    <th scope="col" width="50%">{{ __('Value') }}</th>
                    <th scope="col" width="10%" class="py-1">
                        <button type="button" class="btn btn-success btn-sm" onclick="append_config({})"><i
                                class="bx bx-plus bx-xs align-middle"></i></button>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('plugin-scripts')
    <script id="config-template" type="text/x-handlebars-template">
    <tr id="row-@{{ index }}">
        <td class="row-group">
            <input type="text" class="form-control key" name="config[@{{ index }}][key]">
        </td>
        <td class="row-group">
            <input type="text" class="form-control label" name="config[@{{ index }}][label]">
        </td>
        <td class="row-group">
            <select class="form-select tipe" name="config[@{{ index }}][tipe]" onchange="showOptions('row-@{{ index }}')">
                <option value="text">TEXT</option>
                <option value="string">STRING</option>
                <option value="boolean">BOOLEAN</option>
                <option value="dropdown">DROPDOWN</option>
                <option value="upload">UPLOAD</option>
            </select>
            <input type="text" class="form-control options mt-1 d-none" name="config[@{{ index }}][options]" placeholder="option1;options2;option3">
            <input type="text" class="form-control form-control-sm fileallow mt-1 d-none" name="config[@{{ index }}][fileallow]" placeholder=".png,.jpg">
        </td>
        <td class="row-group">
            <input type="text" class="form-control value" name="config[@{{ index }}][value]">
        </td>
        <td>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="remove_config('@{{ index }}')"><i class="bx bx-trash bx-xs align-middle"></i></button>
        </td>

    </tr>
</script>

    <script type="text/javascript">
        var config_sequence = 0;

        function each_configs(configs) {
            var i = 0;
            $.each(configs, function(i, context) {
                context.its_first = i == 0 ? true : false;
                append_config(context);
                i++;
            });
        }

        function append_config(context) {
            context.sequence = config_sequence;
            // context.index = UUID.create().toString();
            context.index = config_sequence;

            var source = $("#config-template").html();
            var template = Handlebars.compile(source);
            var html = template(context);

            $('#table-configs tbody').append(html);
            $('.select2', $('#table-configs #row-' + context.index)).select2({
                width: '100%',
                theme: "bootstrap"
            });

            $('.key', $('#table-configs #row-' + context.index)).val(typeof context.key != 'undefined' ? context.key : '');
            $('.label', $('#table-configs #row-' + context.index)).val(typeof context.label != 'undefined' ? context.label :
                '');
            $('.tipe', $('#table-configs #row-' + context.index)).val(typeof context.tipe != 'undefined' ? context.tipe :
                '').trigger('change');
            $('.options', $('#table-configs #row-' + context.index)).val(typeof context.options != 'undefined' ? context
                .options : '');
            $('.value', $('#table-configs #row-' + context.index)).val(typeof context.value != 'undefined' ? context.value :
                '');

            config_sequence++;

            $('[data-bs-toggle="popover"]').popover({
                trigger: 'focus',
                html: true
            });
           
        }

        function remove_config(index) {
            $('#row-' + index, $('#table-configs')).remove();
        }

        function set_validation_message(data) {
            $('.row-group.has-error', $('form#my-form')).removeClass('has-error');

            $.each(data.responseJSON.data, function(idx, value) {
                var key = idx.split('.');
                if (key[0] == 'configs') {
                    var field = $('[name="configs[' + key[1] + '][' + key[2] + ']"]');
                    field.closest('.row-group').addClass('has-error');
                    _select2 = field.closest('.row-group').find('.select2-container');
                    if (_select2.length) {
                        _select2.after('<span class="help-block error" style="height:2px !important;"> ' + value +
                            ' </span>');
                    } else {
                        field.after('<span class="help-block error" style="height:2px !important;"> ' + value +
                            ' </span>');
                    }
                }
            });
        }

        function showOptions(row) {
            var tipe = $('.tipe', $(`#${row}`)).val();
            if (tipe == 'dropdown') {
                $('.options', $(`#${row}`)).removeClass('d-none');
            } else if (tipe == 'upload') {
                $('.fileallow', $(`#${row}`)).removeClass('d-none');
                $('.options', $(`#${row}`)).addClass('d-none');
            } else {
                $('.options', $(`#${row}`)).addClass('d-none');
            }
        }
    </script>

    <script type="text/javascript">
        $(function() {
            each_configs({!! isset($data->config) ? $data->config : json_encode([]) !!})
        });
    </script>
@endpush
