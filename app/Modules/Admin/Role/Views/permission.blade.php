@push('plugin-styles')
    <link href="{{ asset('assets/plugins/nestable2/jquery.nestable.min.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        /**
             * Nestable Extras
             */
    
        .nestable-lists {
            display: block;
            clear: both;
            padding: 30px 0;
            width: 100%;
            border: 0;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
        }
    
        #nestable-menu {
            padding: 0;
            /* margin: 20px 0; */
        }
    
        #nestable-output,
        #nestable2-output {
            width: 100%;
            height: 7em;
            font-size: 0.75em;
            line-height: 1.333333em;
            font-family: Consolas, monospace;
            padding: 5px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
    
        #nestable2 .dd-handle {
            color: #fff;
            border: 1px solid #999;
            background: #bbb;
            background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
            background: -moz-linear-gradient(top, #bbb 0%, #999 100%);
            background: linear-gradient(top, #bbb 0%, #999 100%);
        }
    
        #nestable2 .dd-handle:hover {
            background: #bbb;
        }
    
        #nestable2 .dd-item>button:before {
            color: #fff;
        }
    
        @media only screen and (min-width: 700px) {
    
            .dd {
                float: left;
                width: 100%;
            }
    
            .dd+.dd {
                margin-left: 2%;
            }
    
        }
    
        .dd {
            max-width: 100% !important;
        }
    
        .dd-hover>.dd-handle {
            background: #2ea8e5 !important;
        }
    
        /**
             * Nestable Draggable Handles
             */
    
        .dd3-content {
            display: block;
            height: 42px;
            margin: 5px 0;
            padding: 5px 10px 5px 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
    
        .dd3-content:hover {
            color: #2ea8e5;
            background: #fff;
        }
    
        .dd3-content label{
            padding-top: 5px;
        }
    
        .dd-dragel>.dd3-item>.dd3-content {
            margin: 0;
        }
    
        .dd3-item>button {
            margin-left: 5px;
        }
    
        .dd-item > button::before{
            padding-top: 5px;
        }
    
        .dd3-handle {
            position: absolute;
            margin: 0;
            left: 0;
            top: 0;
            cursor: pointer;
            width: 30px;
            height: 41.5px;
            text-indent: 30px;
            white-space: nowrap;
            overflow: hidden;
            border: 1px solid #aaa;
            background: #ddd;
            background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: linear-gradient(top, #ddd 0%, #bbb 100%);
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
    
        .dd3-handle:before {
            content: '≡';
            display: block;
            position: absolute;
            left: 0;
            top: 3px;
            width: 100%;
            text-align: center;
            vertical-align: : middle;
            text-indent: 0;
            color: #fff;
            font-size: 20px;
            font-weight: normal;
            padding-top: 7px;
        }
    
        .dd3-handle:hover {
            background: #ddd;
        }
    </style>
@endpush
<div class="card">
    <div class="card-header">
        <h6 class="">{{ __('Hak Akses') }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div class="btn-group btn-group-sm float-end ml-2" role="Table row actions">
                            <button type="button" class="btn btn-success"
                                data-action="allow-all">{{ __('Izinkan Semua Akses') }}</button>
                            <button type="button" class="btn btn-danger"
                                data-action="disallow-all">{{ __('Hilangkan Semua Akses') }}</button>

                            <button type="button" class="btn btn-outline-secondary"
                                data-action="collapse-all">{{ __('Tutup Semua') }}</button>
                            <button type="button" class="btn btn-outline-primary"
                                data-action="expand-all">{{ __('Melebarkan semua') }}</button>
                        </div>
                    </div>
                </div>
                <div id="nestable-validation" class="alert alert-danger alert-dismissible fade show"
                    style="display:none" role="alert">
                    <i class="feather-16" data-feather="alert-circle"></i>
                    <strong>Oops!</strong> <span id="nestable-validation-msg"></span>.
                </div>
                <div id="nestable-empty" class="alert alert-warning alert-dismissible fade" role="alert">
                    <i class="feather-16" data-feather="alert-circle"></i>
                    <strong>Oops!</strong> {{ __('Tidak ada menu yang tersedia') }}.
                </div>
                <div id="nestable-menu" class="dd"></div>
            </div>
        </div>
    </div>
</div>
@push('custom-scripts')
    <script id="nestable-template" type="text/x-handlebars-template">
    <div class="dd3-content pl-2">
        <label class="mb-0 wrap">
            @{{#if icon}} <i class="@{{icon}} mr-1" ></i> @{{/if}} 
            @{{ label }} <small class="ml-1">@{{ url }}</small>
        </label>

        <div class="btn-group btn-group-sm float-end" role="Table row actions">
            <a rel="tooltip" href="javascript:void(0)" title="{{ __('Akses Setting') }}" class="btn btn-light btn-sm p-1"><span data-id="counter-permission-set-@{{parentIdx}}" id="counter-permission-set-@{{index}}" class="counter-permission-set counter-permission-set-@{{parentIdx}}" data-index="@{{index}}">0</span>/@{{ total_permission }}</a>
            <a rel="tooltip" href="#" title="{{ __('Akses Setting') }}" class="btn btn-light btn-sm p-1" data-bs-toggle="modal" data-bs-target="#modal-mdx-@{{index}}"><i class="bx bx-cog bx-xs align-middle"></i></a>
            @{{#if haveChildren }}
                @{{#if haveParent }}
                <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Izinkan Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".parentToChild-@{{parentToChild}}" data-parent="@{{parentIdx}}" data-action="allowed-child" class="btn btn-success btn-sm p-1"><i class="bx bx-lock-open bx-xs align-middle"></i></button>
                <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Hilangkan akses Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".parentToChild-@{{parentToChild}}" data-parent="@{{parentIdx}}" data-action="disallowed-child" class="btn btn-danger btn-sm p-1"><i class="bx bx-lock bx-xs align-middle"></i></button>
                @{{else }}
                    @{{#if grandparent}}
                    <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Izinkan Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".gpToGrandchild-@{{parentIdx}}" data-parent="@{{parentIdx}}" data-action="allowed-child" class="btn btn-success btn-sm p-1"><i class="bx bx-lock-open bx-xs align-middle"></i></button>
                    <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Hilangkan akses Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".gpToGrandchild-@{{parentIdx}}" data-parent="@{{parentIdx}}" data-action="disallowed-child" class="btn btn-danger btn-sm p-1"><i class="bx bx-lock bx-xs align-middle"></i></button>
                    @{{else}}
                    <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Izinkan Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".children-@{{parentIdx}}" data-parent="@{{parentIdx}}" data-action="allowed-child" class="btn btn-success btn-sm p-1"><i class="bx bx-lock-open bx-xs align-middle"></i></button>
                    <button type="button" data-allowed="false" rel="tooltip" title="{{ __('Hilangkan akses Semua Child yang ada dimenu ') }} @{{ label }}" data-target=".children-@{{parentIdx}}" data-parent="@{{parentIdx}}" data-action="disallowed-child" class="btn btn-danger btn-sm p-1"><i class="bx bx-lock bx-xs align-middle"></i></button>
                    @{{/if}}
                @{{/if}}
            @{{/if}}
        </div>
    </div>

    <div class="modal fade" id="modal-mdx-@{{index}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Akses Setting') }} (@{{label}})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close" onclick="set_counter_permission(@{{index}})"></button>
                </div>

                <div class="modal-body">
                    <div class="btn-group btn-group-sm mb-3" role="Table row actions">
                        <button type="button" class="btn btn-outline-success" onclick="set_checkbox('.permissions-@{{index}}', true)">{{ __('Izinkan Semua Akses') }}</button>
                        <button type="button" class="btn btn-outline-danger" onclick="set_checkbox('.permissions-@{{index}}', false)">{{ __('Hilangkan Semua Akses') }}</button>
                    </div>
                    <table width="100%" class="table">
                        <thead>
                            <tr class="text-white">
                                <th class="p-2">{{ __('Nama') }}</th>
                                <th class="p-2" width="30%">{{ __('Akses') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-2">{{__('Menu Samping')}}</td>
                                <td class="px-2">
                                    <div class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1 form-check form-switch box-visibilities">
                                        @{{#if grandparent}}
                                        <input type="checkbox" id="permissions-visibility-@{{index}}" name="visibilities[]" value="@{{id}}" class="form-check-input custom-control-input permissions-@{{index}} children-@{{parentIdx}} gpToGrandchild-@{{parentIdx}}" data-text-on="{{ __('Tampilkan') }}" data-text-off="{{ __('Sembunyikan') }}" >
                                        @{{else}}
                                        <input type="checkbox" id="permissions-visibility-@{{index}}" name="visibilities[]" value="@{{id}}" class="form-check-input custom-control-input permissions-@{{index}} children-@{{parentIdx}} parentToChild-@{{parentToChild}} gpToGrandchild-@{{parentIdx}}" data-text-on="{{ __('Tampilkan') }}" data-text-off="{{ __('Sembunyikan') }}" >
                                        @{{/if}}
                                        <label class="custom-control-label text-muted" for="permissions-visibility-@{{index}}"></label>
                                    </div>
                                </td>
                            </tr>
                            @{{#each permissions}}
                                <tr>
                                    <td class="px-2">@{{this.name}}</td>
                                    <td class="px-2">
                                        <div class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1 form-check form-switch box-permissions">
                                            @{{#if grandparent}}
                                            <input type="checkbox" id="permissions-@{{../index}}-@{{@index}}" name="permissions[]" value="@{{this.id}}" class="form-check-input custom-control-input permissions-@{{../index}} children-@{{../parentIdx}} gpToGrandchild-@{{../parentIdx}}" data-text-on="{{ __('Mengizinkan') }}" data-text-off="{{ __('Tidak diizinkan') }}" >
                                            @{{else}}
                                            <input type="checkbox" id="permissions-@{{../index}}-@{{@index}}" name="permissions[]" value="@{{this.id}}" class="form-check-input custom-control-input permissions-@{{../index}} children-@{{../parentIdx}} parentToChild-@{{../parentToChild}} gpToGrandchild-@{{../parentIdx}}" data-text-on="{{ __('Mengizinkan') }}" data-text-off="{{ __('Tidak diizinkan') }}" >
                                            @{{/if}}
                                            <label class="custom-control-label text-muted" for="permissions-@{{../index}}-@{{@index}}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @{{/each}}
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="set_counter_permission(@{{index}})" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                </div>
            </div>
        </div>
    </div>
</script>
    <script type="text/javascript">
        var temp_tree_idx = 0;
        var temp_have_parent_children = 0;
        var temp_permissions = {!! json_encode(isset($permissions) ? $permissions : []) !!};
        var temp_visibilities = {!! json_encode(isset($visibilities) ? $visibilities : []) !!};

        function updateOutput(id) {
            $(id).val(window.JSON.stringify($('#nestable-menu').nestable('serialize')));
        }

        function dd_tree(list, parent) {
            var ol = $('<ol \>');
            ol.addClass('dd-list');

            $.each(list, function(idx, val) {
                var li = $('<li \>');
                li.addClass('dd-item dd3-item');
                li.attr('data-id', val.id);

                val.parentIdx = (typeof parent != 'undefined') ? parent : idx;

                if (typeof parent != 'undefined') {
                    val.haveParent = true;
                }

                if (typeof val.children != 'undefined') {
                    val.haveChildren = true;
                    if (val.haveParent) {
                        val.parentToChild = temp_have_parent_children;
                        val.children.forEach(element => {
                            element.parentToChild = temp_have_parent_children;
                            element.grandchild = true;
                        });
                        temp_have_parent_children++;
                    } else {
                        val.children.forEach(element => {
                            if (typeof element.children !== 'undefined' && element.children.length > 0) {
                                val.grandparent = true;
                                return;
                            }
                        });
                    }
                }

                val.index = temp_tree_idx;
                val.total_permission = val.permissions.length + 1;
                temp_tree_idx++;

                var source = document.getElementById("nestable-template").innerHTML;
                var template = Handlebars.compile(source);
                var html = template(val);

                li.append(html.replace(/__grid_doc__/g, val.id));
                if (typeof val.children != 'undefined') {
                    if (val.haveParent) {
                        li.append(dd_tree(val.children, val.parentIdx));
                    } else {
                        li.append(dd_tree(val.children, idx));
                    }
                }

                ol.append(li);
            });

            return ol;
        }

        function dd_msg(s) {
            if (s) {
                $('#nestable-empty').hide();
            } else {
                $('#nestable-empty').show();
            }
        }

        function get_counter_permission(parent) {
            if (typeof parent == 'undefined') {
                $.each($('.counter-permission-set', $('#nestable-menu')), function() {
                    set_counter_permission($(this).data('index'));
                });
            } else {
                $.each($('.counter-permission-set-' + parent, $('#nestable-menu')), function() {
                    set_counter_permission($(this).data('index'));
                });
            }
        }

        function set_checkbox(cb, status) {
            $(cb, $('#nestable-menu')).prop('checked', status);
        }

        function dd_load() {
            $('#nestable-menu').html(loading);
            $.get('{{ route('admin.role.menus') }}', function(out) {
                $('#nestable-menu').html(dd_tree(out.data));
                $('[rel="tooltip"]').tooltip();
                initModalAjax('[data-bs-toggle="modal"]');
                $('#nestable-menu').nestable();
                set_values();

                $.each($('.cb-dynamic-label input[type=checkbox]', $('#nestable-menu')), function() {
                    cbCustomInput(this);
                });

                $('.cb-dynamic-label input[type=checkbox]', $('#nestable-menu')).change(function() {
                    cbCustomInput(this);
                });

                dd_msg(out.data.length ? true : false);

                $('[data-action=allowed-child]').click(function() {
                    set_checkbox($(this).data('target'), true);
                    get_counter_permission($(this).data('parent'));
                });

                $('[data-action=disallowed-child]').click(function() {
                    set_checkbox($(this).data('target'), false);
                    get_counter_permission($(this).data('parent'));
                });
                
            });
        }

        function set_validation_message(data) {
            var data = data.responseJSON.data;
            if (typeof data.permissions != 'undefined') {
                $('#nestable-validation-msg').html(data.permissions);
                $('#nestable-validation').show();
            } else {
                $('#nestable-validation').hide();
            }
        }

        function set_counter_permission(idx) {
            var counter = 0;
            $.each($('.permissions-' + idx), function() {
                if ($(this).is(':checked')) {
                    counter++;
                }
            });

            $('#counter-permission-set-' + idx).html(counter);
        }

        function set_values() {
            $.each(temp_permissions, function(i, v) {
                $('input[value=' + v + ']', $('#nestable-menu .box-permissions')).prop('checked', true);
            });

            $.each(temp_visibilities, function(i, v) {
                $('input[value=' + v + ']', $('#nestable-menu .box-visibilities')).prop('checked', true);
            });

            get_counter_permission();
        }
    </script>
    <script type="text/javascript">
        $(function() {
            dd_load();

            $('[data-action=collapse-all]').click(function() {
                $('#nestable-menu').nestable('collapseAll');
            });

            $('[data-action=expand-all]').click(function() {
                $('#nestable-menu').nestable('expandAll');
            });

            $('[data-action=disallow-all]').click(function() {
                set_checkbox('.custom-control-input', false);
                get_counter_permission();
            });

            $('[data-action=allow-all]').click(function() {
                set_checkbox('.custom-control-input', true);
                get_counter_permission();
            });

        })
    </script>
@endpush
