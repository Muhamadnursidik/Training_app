@extends('layout.app')
@push('plugin-styles')
    <link href="{{asset('assets/plugins/nestable2/jquery.nestable.min.css')}}" rel="stylesheet" type="text/css" />
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
		height: 48px;
		margin: 5px 0;
		padding: 5px 10px 5px 40px;
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
		margin-left: 30px;
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
		height: 48px;
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
@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
<!-- Default Light Table -->
<div class="row">
    <div class="col">
        <div class="card card-small mb-4">
            <div class="card-header border-bottom">
                <a href="{{ route($module . '.create') }}" class="btn btn-primary btn-sm btn-add" data-bs-toggle="modal" data-bs-target="#modal-lg"><i class="bx bx-plus bx-xs align-middle"></i> {{ __('Tambah') }}</a>
                <div class="btn-group btn-group-sm float-end" role="Table row actions">
                    <button type="button" class="btn btn-danger btn-sm" data-action="collapse-all">{{ __('Tutup Semua') }}</button>
                    <button type="button" class="btn btn-success btn-sm" data-action="expand-all">{{ __('Melebarkan semua') }}</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div id="nestable-empty" class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i data-feather="alert-triangle" class="bx bx-error-alt bx-xs"></i>
                            <strong>Oops!</strong> {{ __("Tidak ada menu yang tersedia") }}.
                        </div>
                        <div id="nestable-change-order" class="alert alert-info alert-dismissible fade show" role="alert" style="display:none;height:70px;">
                            <div class="float-start pt-2">
                                <i class="bx bx-error bx-xs"></i>
                                {{ __("Urutan menu telah diubah, klik tombol dibawah untuk meperbaharui urutan") }}.
                            </div>
                            {{ Form::open(['id' => 'form-order-menus', 'route' => [$module . '.save-order'], 'method' => 'put', 'autocomplete' => 'off']) }}
                                <textarea style="display:none;" id="nestable-menu-output-ori"></textarea>
                                <textarea name="sequence" style="display:none;" id="nestable-menu-output"></textarea>
                                <button type="submit" class="btn btn-outline-primary float-end" data-action="save-order"><i class="bx bx-save"></i> {{ __('Simpan Urutan Menu') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="nestable-menu" class="dd"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Default Light Table -->

@endsection

@push('plugin-scripts')
<script src="{{asset('assets/plugins/handlebars/handlebars.min.js')}}"></script>
<script src="{{asset('assets/plugins/nestable2/jquery.nestable.min.js')}}"></script>
<script id="nestable-template" type="text/x-handlebars-template">
    <div class="dd-handle dd3-handle">Drag</div>
    <div class="dd3-content align-middle">
        <label>@{{#if icon}} <i class="@{{icon}} mr-1 bx-xs align-middle"></i> @{{/if}} @{{ label }} <small class="ml-1">@{{ url }}</small></label>

        <div role="group" class="btn-group float-end" role="Table row actions">
            <a rel="tooltip" href="{{ route($module . '.create') }}?parent_id=__grid_doc__" title="{{ __("Tambah Turunan") }}" class="btn btn-outline-success p-1 btn-add" data-bs-toggle="modal" data-bs-target="#modal-lg">
                <i data-feather="plus" class="bx bx-plus-circle bx-xs align-middle"></i>
            </a>
            <a rel="tooltip" href="{{ route($module . '.edit', ['menu' => '__grid_doc__']) }}" title="{{ __("Edit") }}" class="btn btn-outline-secondary p-1 btn-edit" data-bs-toggle="modal" data-bs-target="#modal-lg">
                <i data-feather="edit" class="bx bx-edit bx-xs align-middle"></i>
            </a>
            <a rel="tooltip" href="{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}" title="{{ __("Hapus") }}" class="btn btn-outline-danger btn-delete p-1">
                <i data-feather="trash" class="bx bx-trash bx-xs align-middle"></i>
            </a>
        </div>
    </div>
</script>
<script type="text/javascript">
    function updateOutput(id) {
        $(id).val(window.JSON.stringify($('#nestable-menu').nestable('serialize')));
    }

    function dd_tree(list) {
        var ol = $('<ol \>');
        ol.addClass('dd-list');

        $.each(list, function (idx, val) {
            var li = $('<li \>');
            li.addClass('dd-item dd3-item');
            li.attr('data-id', val.id);

            var source   = document.getElementById("nestable-template").innerHTML;
            var template = Handlebars.compile(source);
            var html     = template(val);

            li.append(html.replace(/__grid_doc__/g, val.id));
            if (typeof val.children != 'undefined') {
                li.append(dd_tree(val.children));
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

    function dd_load() {
        $.get('{{ route($module . '.data') }}', function(out){
            $('#nestable-menu').html(dd_tree(out.data));
            initModalAjax('.btn-add, .btn-edit');
            $('[rel="tooltip"]').tooltip();
            $('#nestable-menu').nestable({
                callback: function(l,e){
                    updateOutput('#nestable-menu-output');

                    var x = $('#nestable-menu-output-ori').val();
                    var y = $('#nestable-menu-output').val();

                    if (x != y) {
                        $('#nestable-change-order').show();
                    } else {
                        $('#nestable-change-order').hide();
                    }
                }
            });

            updateOutput('#nestable-menu-output-ori');
            updateOutput('#nestable-menu-output');

            $('.btn-delete', $('#nestable-menu')).click(function(){
                $(this).myAjax({
                      success: function (data) {
                          $('#nestable-menu').nestable('destroy');
                          dd_load();
                      }
                  }).delete();

                return false;
            });

            dd_msg(out.data.length ? true : false);

            
        });
    }
</script>
<script type="text/javascript">
    $(function(){
        initModalAjax('.btn-add, .btn-edit');
        dd_load();

        $('[data-action=collapse-all]').click(function(){
            $('#nestable-menu').nestable('collapseAll');
        });

        $('[data-action=expand-all]').click(function(){
            $('#nestable-menu').nestable('expandAll');
        });

        $('form#form-order-menus').submit(function(e){
          e.preventDefault();
          $(this).myAjax({
              waitMe: '.card-body',
              success: function (data) {
                  $('#nestable-change-order').hide();
              }
          }).submit();
        });
    })
</script>
@endpush
