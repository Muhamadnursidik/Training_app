@extends('layout.app')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2">
  <div>
    <h4 class="mb-2 mb-md-0">Beranda</h4>
  </div>
</div>
<div class="row">
  <div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
      <div class="card-body">
        <div class="row align-items-start mb-2">
          <div class="col-md-12">
            @include('components.information')
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

@endsection

@push('plugin-scripts')
  {{-- <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script> --}}
@endpush

@push('custom-scripts')
<script type="text/javascript">
    
    $(function(){
        initPage();
        initModalAjax();
    })
</script>
@endpush
