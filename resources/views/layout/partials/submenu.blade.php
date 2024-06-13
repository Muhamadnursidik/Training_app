@if (isset($menus))
    <div id="{{ $menuName }}" class="menu-dropdown collapse">
        <ul class="nav nav-sm flex-column">
            @foreach ($menus as $menu)
                @php
                    $menuName = str_replace(' ', '-', $menu['label']);
                @endphp
                <li class="nav-item {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                    @if ($menu['url'] == '#' && (isset($menu['children']) && count($menu['children']) > 0))
                        <a href="#{{ $menuName }}" data-bs-toggle="collapse" aria-expanded="false"
                            class="nav-link {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                            
                            {{ $menu['label'] }}
                            {!! isset($menu['children']) && count($menu['children']) > 0 ? '<i class="link-arrow fas fa-angle-up"></i>' : '' !!}
                        </a>
                        @if (isset($menu['children']))
                            @include('layout.partials.submenu', [
                                'menus' => $menu['children'],
                                'menuName' => $menuName,
                            ])
                        @endif
                    @else
                        <a href="{{ $menu['url'] }}"
                            class="nav-link {{ in_array($menu['slug'], $currentUrl) ? 'active' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif

                </li>
            @endforeach
        </ul>
    </div>
@endif
