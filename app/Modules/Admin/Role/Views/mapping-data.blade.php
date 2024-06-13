<div class="row">
    <div class="col-sm-12">
        {{-- <h6 class="fw-bold">{{ __('Maping Role') }}</h6> --}}
        @include('components.tools-filter-with-export', [
            'table_id' => '#table-features',
            'title' => 'Mapping Role',
            'exports'  => [
                            ['id' => 'export-excel', 'icon' => 'file-text', 'class' => 'bx bxs-file text-success', 'title' => 'Export Excel']
                        ]
            ])
     
        <table id="table-features" class="table">
            <thead class="bg-light">
                <tr>
                    <th scope="col" width="20%">{{ __('Jabatan') }}</th>
                    <th scope="col" width="30%">{{ __('Divisi / Bagian / Bidang') }}</th>
                    <th scope="col" width="40%">{{ __('Kantor') }}</th>
                    <th scope="col" width="5%">
                        <button type="button" class="btn btn-primary btn-sm" onclick="append_feature({})"><i
                                class="bx bx-plus-circle bx-xs align-middle"></i></button>
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
            <select class="form-control select2-custom jabatan_code" name="mapping[@{{ index }}][jabatan_code]">
                @foreach($options_jabatan as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </td>

        <td class="row-group">
            <select id="select2-multiple2-bagian-@{{index}}" class="form-control select2-selectall-bagian bagian_code" name="mapping[@{{ index }}][bagian_code][]" multiple="multiple">
                @foreach($options_bagian as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>

            <div class="btn-group btn-group-sm mt-1">
                <button class="btn btn-sm btn-primary selected-all-bagian text-end" type="button">Select all</button>
                <button class="btn btn-sm btn-danger unselected-all-bagian text-end" type="button">Unselect all</button>
            </div>
        </td>
        <td class="row-group">
            <select id="select2-multiple2-@{{index}}" class="form-control select2-selectall unit_kerja_code" name="mapping[@{{ index }}][unit_kerja_code][]" multiple="multiple">
                @foreach($options_unit_kerja as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
            <div class="btn-group btn-group-sm mt-1">
                <button class="btn btn-sm btn-primary selected-all text-end" type="button">Select all</button>
                <button class="btn btn-sm btn-danger unselected-all text-end" type="button">Unselect all</button>
            </div>
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
            $.each(features, function(i, context) {
                context.its_first = i == 0 ? true : false;
                append_feature(context);
                i++;
            });
        }

        function append_feature(context) {
            
            context.sequence = feature_sequence;
            // context.index = UUID.create().toString();
            context.index = feature_sequence;

            var source = $("#feature-template").html();
            var template = Handlebars.compile(source);
            var html = template(context);

            $('#table-features tbody').append(html);

            $.fn.select2.amd.require([
                'select2/selection/single',
                'select2/selection/placeholder',
                'select2/selection/allowClear',
                'select2/dropdown',
                'select2/dropdown/search',
                'select2/dropdown/attachBody',
                'select2/utils'
            ], function(SingleSelection, Placeholder, AllowClear, Dropdown, DropdownSearch, AttachBody, Utils) {

                var SelectionAdapter = Utils.Decorate(
                    SingleSelection,
                    Placeholder
                );

                SelectionAdapter = Utils.Decorate(
                    SelectionAdapter,
                    AllowClear
                );

                var DropdownAdapter = Utils.Decorate(
                    Utils.Decorate(
                        Dropdown,
                        DropdownSearch,
                    ),
                    AttachBody
                );

                var base_element = $('.select2-selectall', $('#table-features #row-' + context.index));

                base_element.select2({
                    placeholder: 'Pilih kantor',
                    dropdownParent: $('.modal-body', $('#modal-xl')),
                    AllowClear: false,
                    selectionAdapter: SelectionAdapter,
                    dropdownAdapter: DropdownAdapter,
                    templateSelection: function(data) {
                        
                        var selected = (base_element.val() || []).length;
                        var total = $('option', base_element).length;
                        return "Terpilih " + selected + " dari " + total;
                    }
                });

                var bagian_element = $('.select2-selectall-bagian', $('#table-features #row-' + context.index));

                bagian_element.select2({
                    placeholder: 'Pilih Bagian',
                    dropdownParent: $('.modal-body', $('#modal-xl')),
                    AllowClear: false,
                    selectionAdapter: SelectionAdapter,
                    dropdownAdapter: DropdownAdapter,
                    templateSelection: function(data) {

                        var selectedbagian = (bagian_element.val() || []).length;
                        var totalbagian = $('option', bagian_element).length;
                        
                        return "Terpilih " + selectedbagian + " dari " + totalbagian;
                    }
                });

                $('.select2-custom', $('#table-features #row-' + context.index)).select2({
                    dropdownParent: $('.modal-body', $('#modal-xl'))
                });

                $(".selected-all", $('#table-features #row-' + context.index)).click(function() {
                    $('.select2-selectall > option', $('#table-features #row-' + context.index)).prop(
                        "selected", "selected");
                    $('.select2-selectall', $('#table-features #row-' + context.index)).trigger("change");
                });

                $(".unselected-all", $('#table-features #row-' + context.index)).click(function() {
                    $('.select2-selectall', $('#table-features #row-' + context.index)).val('').trigger(
                        "change");
                });

                $(".selected-all-bagian", $('#table-features #row-' + context.index)).click(function() {
                    $('.select2-selectall-bagian > option', $('#table-features #row-' + context.index)).prop(
                        "selected", "selected");
                    $('.select2-selectall-bagian', $('#table-features #row-' + context.index)).trigger("change");
                });

                $(".unselected-all-bagian", $('#table-features #row-' + context.index)).click(function() {
                    $('.select2-selectall-bagian', $('#table-features #row-' + context.index)).val('').trigger(
                        "change");
                });

                initPage();

            });

            $('.bagian_code', $('#table-features #row-' + context.index)).val(typeof context.bagian_code != 'undefined' ?
                $.parseJSON(context.bagian_code) : '').trigger('change');

            $('.jabatan_code', $('#table-features #row-' + context.index)).val(typeof context.jabatan_code != 'undefined' ?
                context.jabatan_code : '').trigger('change');

            $('.unit_kerja_code', $('#table-features #row-' + context.index)).val(typeof context.unit_kerja_code !=
                'undefined' ?
                $.parseJSON(context.unit_kerja_code) : '').trigger('change');

            feature_sequence++;


        }

        function remove_feature(index) {
            $('#row-' + index, $('#table-features')).remove();
        }

        function set_validation_message(data) {
            $('.row-group.has-error', $('form#my-form')).removeClass('has-error');

            $.each(data.responseJSON.data, function(idx, value) {
                var key = idx.split('.');
                if (key[0] == 'features') {
                    var field = $('[name="features[' + key[1] + '][' + key[2] + ']"]');
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
    </script>

    <script type="text/javascript">
        $('.export-excel').on('click', function(e) {
            let elexport = $(this).attr('id');
            let type = $("<input>").attr("type", "hidden").attr("name", "type").val(elexport);
            let url = "{{ route($module . '.report', ['id' => encrypt($data["id"]), 'type' => 'export-excel'] ) }}";

            window.open(url ,'_blank');
        });
        $(function() {
            each_features({!! json_encode(isset($mapping) ? $mapping : []) !!})
        });
    </script>
@endpush
