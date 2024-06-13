<h6 class="m-0 mt-2 float-start">
    {{ isset($title) ? $title : __('Filter') }}
</h6>

<div class="toolsFilter btn-group float-end" data-table="{{ $table_id }}">

    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="true">
        <i class="bx bx-menu bx-xs" data-feather="menu"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="javascript:void(0);" class="dropdown-item reload-table"><i class="bx bx-refresh bx-xs" data-feather="refresh-cw"></i> {{ __('Muat ulang') }}</a>
        <a href="javascript:void(0);" class="dropdown-item reset-filter"><i class="bx bx-reset bx-xs" data-feather="rotate-ccw"></i> {{ __('Reset Filter') }}</a>
    </div>
</div>
