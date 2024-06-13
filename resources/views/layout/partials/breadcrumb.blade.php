<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-sm-0">{!! $pageTitle !!}</h5>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @php
                        $i = 1;
                    @endphp
                    @if (!empty($breadcrumb))
                        @foreach ($breadcrumb as $item)
                            @if ($i == 1)
                                <li class="breadcrumb-item fw-bold"><a href="#">{{ $item }}</a></li>
                            @else
                                <li class="breadcrumb-item active fw-bold" aria-current="page">{{ $item }}</li>
                            @endif
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>