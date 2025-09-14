<h6 class="m-0 mt-2 float-start">
    {{ isset($title) ? $title : __('Filter') }}
</h6>

<div class="toolsFilter btn-group float-end" data-table="{{ $table_id }}">
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
