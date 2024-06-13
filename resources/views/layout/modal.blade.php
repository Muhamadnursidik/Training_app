<div class="modal-header">
    <h5 class="modal-title">@yield('title')</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
</div>

@yield('content')

{{-- @minify('plugin-scripts') --}}
@stack('plugin-scripts')
{{-- @endminify --}}

{{-- @minify('custom-scripts') --}}
@stack('custom-scripts')
{{-- @endminify --}}
