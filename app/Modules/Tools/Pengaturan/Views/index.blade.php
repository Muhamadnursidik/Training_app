@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))

<div class="row">
    <div class="col-md-3">
        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills" id="chart-tabs" aria-orientation="vertical">
            @foreach ($pengaturan as $item)
                <a class="nav-item nav-link {{ $loop->iteration === 1  ? 'active' : '' }}" style="cursor: pointer;" onclick="tabsChart(this.id)"
                    data-source="{{ route($module.'.page') }}?tab={{ $item->code }}"
                    data-bs-target="#{{ $item->code }}_page" 
                    class="media_node span" id="{{ $item->code }}_tab"
                    data-bs-toggle="tabajax" rel="bs-tooltip">
                    <i class="bx bx-cog bx-xs align-middle"></i> {{ isset($item->name) ? strtoupper($item->name) : strtoupper($item->code) }}
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content text-muted mt-3 mt-lg-0" id="v-tabContent">
            @foreach ($pengaturan as $item)
                <div class="tab-pane" role="tabpanel" aria-labelledby="v-{{ $item->code }}-tab" id="{{ $item->code }}_page"></div>
            @endforeach
        </div>
    </div>

</div>

@endsection

@push('plugin-scripts')
<script type="text/javascript">

    function reloadContent(){
        $.each($('.tab-pane'), (i, v) => {
            $(v).html('')
        })
    }

    function tabsChart(id) {

        let tabactive = $('div#chart-tabs a.active');
        let tabID = tabactive.attr('id');
        let prevTargetID = $('#' + tabID).data('bs-target');

        $(prevTargetID).empty()

        $('.nav-item', $('#chart-tabs')).removeClass('active')
        var $this = $('#' + id),
            loadurl = $this.attr('data-source'),
            targ = $this.attr('data-bs-target');

        $(targ).html(loading);

        $.get(loadurl, function(data) {
            reloadContent()
            $(targ).html(data);
            initPage();
        });
        $this.tab('show');
    }

    $(function(){
        initPage();
        tabsChart('{{ $pengaturan->first()->code }}_tab')
    })
</script>
@endpush
