<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('Fitur Aksi') }}</h6>
        <table id="table-actions_permissions" class="table">
            <thead class="bg-light">
                <tr>
                    <th scope="col" width="50%">{{ __('Permission') }}</th>
                    <th scope="col" width="10%" class="py-1">
                        <button type="button" class="btn btn-danger btn-xs" onclick="append_actions_permission({})"><i class="bx bx-plus-circle"></i></button>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('plugin-scripts')
<script id="actions_permission-template" type="text/x-handlebars-template">
    <tr id="row-@{{ index }}">
        <td class="row-group">
            <select class="form-control select2 code" onchange="actions_permission('@{{ index }}')" data-bs-target="#name-@{{ index }}" id="code-@{{ index }}" name="actions_permission[@{{ index }}][code]">
                @foreach($options_actions as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-xs btn-outline-danger" onclick="remove_actions_permission('@{{ index }}')"><i class="bx bx-trash bx-xs"></i></button>
        </td>

    </tr>
</script>

<script type="text/javascript">
    var actions_permission_sequence = 0;
    function each_actions_permissions(actions_permissions) {
        var i = 0;
		$.each(actions_permissions, function(i, context){
			context.its_first = i == 0 ? true : false;
            console.log(context);
			append_actions_permission(context);
			i++;
		});
    }

    function append_actions_permission(context) {
        context.sequence = actions_permission_sequence;
        // context.index = UUID.create().toString();
        context.index = actions_permission_sequence;

		var source   = $("#actions_permission-template").html();
		var template = Handlebars.compile(source);
		var html     = template(context);

        $('#table-actions_permissions tbody').append(html);
        $('.select2', $('#table-actions_permissions #row-' + context.index)).select2({theme:'bootstrap-5',width:'100%',dropdownParent: $('#modal-lg')})

        $('.code', $('#table-actions_permissions #row-' + context.index)).val(typeof context.code != 'undefined' ? context.code : '').trigger('change');

        actions_permission_sequence++;
    }

    function remove_actions_permission(index) {
        $('#row-' + index, $('#table-actions_permissions')).remove();
    }

    function actions_permission(id){
        var target = $('#code-'+id).data('target')
        var text = $('#code-'+id).text()

       console.log(text);
    }

    function set_validation_message(data) {
        $('.row-group.has-error', $('form#my-form')).removeClass('has-error');

        $.each(data.responseJSON.data, function(idx, value){
            var key = idx.split('.');
            if (key[0] == 'actions') {
                var field = $('[name="actions['+ key[1] +']['+ key[2] +']"]');
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
        each_actions_permissions({!! json_encode(isset($data->actions_permission) ? $data->actions_permission : []) !!})
    });
</script>
@endpush
