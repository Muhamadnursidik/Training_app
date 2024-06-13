<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('Konfigurasi') }}</h6>
        <table id="table-configs" class="table">
            <thead class="bg-light">
                <tr>
                    <th scope="col">{{ __('Key') }}</th>
                    <th scope="col" width="50%">{{ __('Value') }}</th>
                    <th scope="col" width="10%" class="py-1">
                        <button type="button" class="btn btn-success btn-xs" onclick="append_config({})"><i class="feather-16" data-feather="plus"></i></button>
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
            <input type="text" class="form-control form-control-sm key" name="config[@{{ index }}][key]">
        </td>
        <td class="row-group">
            <input type="text" class="form-control form-control-sm value" name="config[@{{ index }}][value]">
        </td>
        <td>
            <button type="button" class="btn btn-xs btn-outline-danger" onclick="remove_config('@{{ index }}')"><i class="feather-16" data-feather="trash"></i></button>
        </td>

    </tr>
</script>

<script type="text/javascript">
    var config_sequence = 0;
    function each_configs(configs) {
        var i = 0;
		$.each(configs, function(i, context){
			context.its_first = i == 0 ? true : false;
			append_config(context);
			i++;
		});
    }

    function append_config(context) {
        context.sequence = config_sequence;
        // context.index = UUID.create().toString();
        context.index = config_sequence;

		var source   = $("#config-template").html();
		var template = Handlebars.compile(source);
		var html     = template(context);

        $('#table-configs tbody').append(html);
        $('.select2', $('#table-configs #row-' + context.index)).select2({
            width: '100%',
            theme: "bootstrap"
        });

        $('.key', $('#table-configs #row-' + context.index)).val(typeof context.key != 'undefined' ? context.key : '');
        $('.value', $('#table-configs #row-' + context.index)).val(typeof context.value != 'undefined' ? context.value : '');
       
        config_sequence++;

        $('[data-bs-toggle="popover"]').popover({trigger:'focus',html:true});
        feather.replace();
    }

    function remove_config(index) {
        $('#row-' + index, $('#table-configs')).remove();
    }

    function set_validation_message(data) {
        $('.row-group.has-error', $('form#my-form')).removeClass('has-error');

        $.each(data.responseJSON.data, function(idx, value){
            var key = idx.split('.');
            if (key[0] == 'configs') {
                var field = $('[name="configs['+ key[1] +']['+ key[2] +']"]');
                field.closest('.row-group').addClass('has-error');
                _select2 = field.closest('.row-group').find('.select2-container');
                if (_select2.length) {
                    _select2.after('<span class="help-block error" style="height:2px !important;"> ' + value + ' </span>');
                } else {
                    field.after('<span class="help-block error" style="height:2px !important;"> ' + value + ' </span>');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(function(){
        each_configs({!! (isset($data->config) ? $data->config : json_encode([])) !!})
    });
</script>
@endpush
