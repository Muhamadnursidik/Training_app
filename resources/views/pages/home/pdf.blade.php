@extends('layout.modal')

@section('title', __('Petunjuk teknis operasional'))

@section('content')
    <div class="modal-body pb-2">
        @if (($url))
            <iframe src="{{ $url }}" frameborder="0" width="100%" height="600px"></iframe>
        @else
            <h4 class="text-center">Usermanual tidak ditemukan.</h4>
        @endif
    </div>
    <div class="modal-footer">
        @if (($url))
            <a class="btn btn-warning" href="{{ $url }}" target="_blank">{{ __('Buka Pada Tab Baru') }}</a>
        @endif
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
    </div>
@endsection
