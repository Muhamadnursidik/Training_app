<div class="row">
    <div class="col-sm-12">
        <h6>{{ __('Fitur') }}</h6>
        <table id="table-features" class="table">
            <thead class="bg-light">
                <tr>
                    <th scope="col">{{ __('Nama') }}</th>
                    <th scope="col" width="50%">{{ __('Kode Permission') }}</th>
                    <th scope="col" width="10%">
                        <button type="button" class="btn btn-primary btn-sm" onclick="append_feature({})"><i class="bx bx-plus-circle bx-xs align-middle"></i></button>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('plugin-scripts')
<script id="feature-template" type="text/x-handlebars-template">
    <tr id="row-@{{ index }}">
        <td class="row-group">
            <input type="hidden" name="features[@{{ index }}][sequence]" value="@{{ sequence }}">
            <input type="text" class="form-control name" name="features[@{{ index }}][name]">
        </td>
        <td class="row-group">
            <select class="form-control select2-custom permission" name="features[@{{ index }}][permission_id]">
                @foreach($permissions as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="remove_feature('@{{ index }}')"><i class="bx bx-trash bx-xs align-middle"></i></button>
        </td>

    </tr>
</script>

<script type="text/javascript">
    var feature_sequence = 0;
    function each_features(features) {
        var i = 0;
		$.each(features, function(i, context){
			context.its_first = i == 0 ? true : false;
			append_feature(context);
			i++;
		});
    }

    function append_feature(context) {
        context.sequence = feature_sequence;
        // context.index = UUID.create().toString();
        context.index = feature_sequence;

		var source   = $("#feature-template").html();
		var template = Handlebars.compile(source);
		var html     = template(context);

        $('#table-features tbody').append(html);
        $('.select2-custom', $('#table-features #row-' + context.index)).select2({
            dropdownParent: $('.modal-body', $('#modal-lg'))
        });

        $('.name', $('#table-features #row-' + context.index)).val(typeof context.name != 'undefined' ? context.name : '');
        $('.permission', $('#table-features #row-' + context.index)).val(typeof context.id != 'undefined' ? context.id : '').trigger('change');

        feature_sequence++;

        
    }

    function remove_feature(index) {
        $('#row-' + index, $('#table-features')).remove();
    }

    function set_validation_message(data) {
        $('.row-group.has-error', $('form#my-form')).removeClass('has-error');

        $.each(data.responseJSON.data, function(idx, value){
            var key = idx.split('.');
            if (key[0] == 'features') {
                var field = $('[name="features['+ key[1] +']['+ key[2] +']"]');
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
        each_features({!! json_encode(isset($features) ? $features : []) !!})
    });
</script>
@endpush
