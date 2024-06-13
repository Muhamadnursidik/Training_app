{{-- @push('plugin-styles')
<link href="{{ asset(mix('assets/plugins/datatables-net/dataTables.bootstrap5.min.css')) }}" rel="stylesheet" type="text/css" />
@endpush --}}

@push('plugin-scripts')
<script src="{{ asset(mix('assets/plugins/datatables-net/jquery.dataTables.js')) }}"></script>
<script src="{{ asset(mix('assets/plugins/datatables-net/dataTables.bootstrap5.min.js')) }}"></script>
<script src="{{ asset(mix('js/datatable.min.js')) }}"></script>
@endpush