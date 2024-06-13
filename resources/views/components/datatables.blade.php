<div class="table-responsive-sm">
<table id="{{ isset($id) ? $id :  'dt-basic' }}"
       class="{{ isset($class) ? $class :  'table table-border' }} "
       style="{{ isset($style) ? $style :  'margin-top: 0 !important; padding: 0.2em !important;max-width:100%;' }} "
       data-table-source="{{ isset($data_source) ? $data_source : '' }}"
       data-table-filter="{{ isset($form_filter) ? $form_filter :  '#form-filter' }}"
       data-auto-filter="true">
    <thead>
	    <tr>
            @isset($delete_action)
            <th class="toolsTable no-sort p-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle pt-1 pb-1 pr-2 pl-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-list-ul bx-xs" data-feather="menu"></i>
                    </button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#btn-checked-all"><i class="bx bx-select-multiple bx-xs"></i> {{ __('Pilih semua') }}</a>
                        <a class="dropdown-item" href="#btn-unchecked-all"><i class="bx bx-x-circle bx-xs" data-feather="x-circle"></i> {{ __('Batalkan semua') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item btn-delete-selected" href="{{isset($delete_action) ? $delete_action : ''}}"><i class="bx bx-trash bx-xs" data-feather="trash"></i> {{ __('Hapus yang dipilih') }}</a>
                    </div>
                </div>
            </th>
            @endisset
	    	@if (isset($header) && count($header) > 0)
	    		@foreach($header as $key => $value)
		    		<th>{{ __($value) }}</th>
		    	@endforeach
            @endif
           
            @if ((isset($aksi)) ? $aksi : true)
            <th class="no-sort">{{ __('Aksi') }}</th>
            @endif
            
	    </tr>
    </thead>
</table>
</div>
