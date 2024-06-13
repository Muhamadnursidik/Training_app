<h6 class="m-0 mt-2 float-start">
    {{ isset($title) ? $title : __('Filter') }}
</h6>

<div class="toolsExport btn-group float-end" data-table="{{ $table_id }}">

    <button type="button" class="btn btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bx bx-menu bx-xs" data-feather="menu"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        @php
        $exports = isset($exports) ? $exports : [['id' => 'export-pdf', 'class' => 'bx bxs-file-pdf text-danger', 'title' => 'Export Pdf'],
                                                 ['id' => 'export-excel', 'class' => 'bx bxs-file text-success', 'title' => 'Export Excel']];
        @endphp
        @if (isset($exports))
            @foreach ($exports as $item)
                @if (isset($item['id']))
                <button class="dropdown-item {{ $item['id'] }}" id="{{ $item['id'] }}" type="button"><i
                    class="{{ $item['class'] }}"></i> {{ $item['title'] }}</button>
                @elseif(isset($item['divider']))    
                <div class="dropdown-divider"></div>
                @endif
                
            @endforeach
        @endif

    </div>
</div>
