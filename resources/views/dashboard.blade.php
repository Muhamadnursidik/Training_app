@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2">
        <div>
            <h4 class="mb-2 mb-md-0">Beranda</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    @include('components.information')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset(mix('assets/plugins/highcharts/highcharts.js')) }}" type="text/javascript"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset(mix('js/graph.js')) }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            initPage();
        })
    </script>
@endpush
