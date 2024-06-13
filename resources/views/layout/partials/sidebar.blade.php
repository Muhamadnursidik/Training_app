<div class="app-menu navbar-menu">
    <div class="navbar-brand-box bg-white">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                @if (file_exists(public_path() . '/img/logo.png'))
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @else
                    <img src="{{ asset('img/logo-bartech.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @endif
            </span>
            <span class="logo-lg">
                @if (file_exists(public_path() . '/img/logo.png'))
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @else
                    <img src="{{ asset('img/logo-bartech.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @endif
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                @if (file_exists(public_path() . '/img/logo.png'))
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @else
                    <img src="{{ asset('img/logo-bartech.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @endif
            </span>
            <span class="logo-lg">
                @if (file_exists(public_path() . '/img/logo.png'))
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="240">
                @else
                    <img src="{{ asset('img/logo-bartech.png') }}" class="img-fluid ml-1" alt="Responsive image"
                        style="height: 60px !important;" width="160">
                @endif
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="mdi mdi-record-circle-outline"></i>
        </button>
    </div>


    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Navigasi Menu</span></li>
                @isset($menus)

                    @foreach ($menus as $menu)
                        @php
                            $menuName = str_replace(' ', '-', $menu['label']);
                        @endphp
                        <li class="nav-item {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                            @if ($menu['url'] == '#' && isset($menu['children']))
                                <a href="#sidebar{{ $menuName }}" data-bs-toggle="collapse" aria-expanded="false" aria-controls="sidebar{{ $menuName }}"
                                    class="nav-link menu-link {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                                    <i class="{{ $menu['icon'] }} align-middle"></i>
                            
                                    <span data-key="t-{{$menuName}}">{{ $menu['label'] }}</span>
                                    
                                    {!! isset($menu['children']) && count($menu['children']) > 0
                                        ? '<i class="link-arrow " data-feather="chevron-up"></i>'
                                        : '' !!}
                                </a>
                                @if (isset($menu['children']))
                                    @include('layout.partials.submenu', [
                                        'menus' => $menu['children'],
                                        'menuName' => 'sidebar'.$menuName,
                                    ])
                                @endif
                            @else
                                <a href="{{ ($menu['url'] != '#') ? route($menu['url']) : $menu['url'] }}"
                                    class="nav-link menu-link {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                                    <i class="{{ $menu['icon'] }} align-middle"></i>
                                    <span data-key="t-{{$menuName}}">{{ $menu['label'] }}</span>
                                </a>
                            @endif

                        </li>
                    @endforeach
                @endisset
            </ul>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script>
        function parentSelected() {
            var active = $('.nav-link.active');
            var parent = active.parent().parent().parent()

            parent.addClass('show')
            parent.parent().addClass('active').children().addClass('active').parent().parent().parent().parent().addClass(
                'active')

            active.parent().parent().parent().parent().addClass('active');

            $('[href="#' + parent.attr('id') + '"]').attr('aria-expanded', true)
        }

        $(function() {
            parentSelected()
        })
    </script>
@endpush
