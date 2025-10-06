<h6 class="m-0 mt-2 float-start">
    {{ isset($title) ? $title : __('Filter') }}
</h6>

<div class="toolsFilter btn-group float-end" data-table="{{ $table_id }}">
<<<<<<< HEAD

    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="true">
        <i class="bx bx-menu bx-xs" data-feather="menu"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="javascript:void(0);" class="dropdown-item reload-table"><i class="bx bx-refresh bx-xs" data-feather="refresh-cw"></i> {{ __('Muat ulang') }}</a>
        <a href="javascript:void(0);" class="dropdown-item reset-filter"><i class="bx bx-reset bx-xs" data-feather="rotate-ccw"></i> {{ __('Reset Filter') }}</a>
        <div class="dropdown-divider"></div>
            @if (in_array(Route::currentRouteName(), ['master.dataliburnasional.index', 'master.rencanaproject.index', 'master.penyesuaianrencanaproject.index']))
                <a href="{{ route($module . '.export', ['type' => 'pdf']) }}" class="dropdown-item"><i class="bx bx-file bx-xs text-danger"></i> Export PDF</a>
                <a href="{{ route($module . '.export', ['type' => 'excel']) }}" class="dropdown-item"><i class="bx bx-spreadsheet bx-xs text-success"></i> Export Excel</a>
                <a href="{{ route($module . '.export', ['type' => 'word']) }}" class="dropdown-item"><i class="bx bx-file bx-xs text-info"></i> Export Word</a>
            @endif
    </div>
</div>
=======
    <!-- Export Button -->
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bx bx-cloud-download bx-xs"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item export-btn" href="#" data-format="pdf">
            <i class="bx bxs-file-pdf bx-xs text-danger me-2"></i> Export PDF
        </a>
        <a class="dropdown-item export-btn" href="#" data-format="excel">
            <i class="bx bx-table bx-xs text-success me-2"></i> Export Excel
        </a>
        <a class="dropdown-item export-btn" href="#" data-format="word">
            <i class="bx bxs-file-doc bx-xs text-primary me-2"></i> Export Word
        </a>
    </div>

    <!-- Tools Menu Button (existing) -->
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="true">
        <i class="bx bx-dots-vertical-rounded bx-xs"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="javascript:void(0);" class="dropdown-item reload-table"><i class="bx bx-refresh bx-xs me-2"></i> {{ __('Muat ulang') }}</a>
        <a href="javascript:void(0);" class="dropdown-item reset-filter"><i class="bx bx-reset bx-xs me-2"></i> {{ __('Reset Filter') }}</a>
    </div>
</div>
>>>>>>> dev
