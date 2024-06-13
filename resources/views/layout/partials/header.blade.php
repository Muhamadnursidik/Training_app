<header id="page-topbar" class="topbar-shadow">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo.png') }}" alt="" height="60" width="160">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" height="60" width="160">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo.png') }}" alt="" height="60" width="160">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" height="60" width="160">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                <form class="app-search">
                    <div class="position-relative">
                        <h3 class="mb-1 mt-2 d-none d-md-block">{{ config('data.app_name') }}</h3>
                        <h3 class="mb-1 mt-2 d-none d-sm-block d-md-none">{{ config('data.app_sort_name') }}</h3>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class="bx bx-fullscreen bx-sm"></i>
                    </button>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="bx bx-book bx-sm"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-sm p-0 dropdown-menu-end"
                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 58px, 0px);"
                        data-popper-placement="bottom-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"> {{ __('User Manual') }} </h6>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <div class="row g-0 p-1">
                                @if (in_array('pdf', explode('|', config('app.usermanual'))))
                                    <div class="col-6 text-center m-auto">
                                        <a class="dropdown-icon-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70 usermanual"
                                        href="{{ route('usermanual') }}"
                                        data-bs-target="#modal-xl-no-centered"
                                        data-bs-toggle="modal">
                                            <i class="bx bxs-file-pdf bx-md"></i>
                                            {{-- <span>PDF</span> --}}
                                        </a>
                                    </div>
                                @endif
                                {{-- <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <i class="bx bxs-movie-play bx-md"></i> --}}
                                        {{-- <span>VIDIO</span> --}}
                                    {{-- </a>
                                </div> --}}

                            </div>

                        </div>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                        onclick="toggleNotifications()" data-bs-auto-close="outside" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-bell bx-sm" id="notif-icon"></i>
                        <span id="notif-alert"></span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">

                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span class="badge bg-light-subtle text-body fs-13" id="textnotif"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content position-relative" id="notificationDropdownContent">

                        </div>

                    </div>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ asset('img/avatars/avatar.png') }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name }}</span>
                                <span
                                    class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{ auth()->user()->roles[0]->name }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">
                            <span class="fw-bold">{{ auth()->user()->name }}</span>
                            <div class="text-truncate">{{ auth()->user()->unitkerja->name }}</div>
                        </h6>
                        {{-- <a class="dropdown-item" href="pages-profile.html"><i
                                class="bx bx-user-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a> --}}
                        {{-- <a class="dropdown-item" href="{{ route('mapping.notification.index') }}"><i
                                class="bx bx-envelope text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Inbox</span></a> --}}
                        <a class="dropdown-item change-password" 
                            href="{{ route('admin.changepassword') }}"
                            id="change-password"
                            data-bs-target="#modal-md"
                            data-bs-toggle="modal">
                            <i class="bx bx-lock text-muted fs-16 align-middle me-1"></i> 
                            <span class="align-middle">Ganti Password</span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="bx bx-exit text-muted fs-16 align-middle me-1"></i> 
                                <span class="align-middle" data-key="t-logout">Logout</span></a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('custom-scripts')
    <script>
        function getNotifications() {
            $.get('{{ route('mapping.notification.count') }}', (e) => {
                if (e.data > 0) {
                    document.getElementById("textnotif").innerHTML = e.data;
                    $('#notif-icon').addClass('bx-tada');
                    $('#notif-alert', $('#notificationDropdown')).html(
                        '<span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">' +
                        e.data + '<span class="visually-hidden">unread messages</span>');
                }
            })
        }

        function toggleNotifications() {
            $('#notificationDropdownContent').html(loading)
            $.get('{{ route('mapping.notification.data') }}', (e) => {
                let html = '';
                if (e.data.length) {
                    html +=
                        '<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom"><p class="mb-0 font-weight-medium">Notifikasi Baru</p></div>';
                    html += '<div class="p-1 overflow-auto" style="max-height: 300px;">';

                    $.each(e.data, (i, v) => {

                        html += '<a href="' + v.url +
                            '" class="dropdown-item d-flex align-items-center py-2 notif-click">';
                        html += '<div class="me-3"><i class="fas ' + ((v.type == 'R') ?
                                'fa-calendar-check text-primary fa-lg' : 'fa-circle-info text-info fa-lg') +
                            '"></i></div>';
                        html +=
                            '<div class="d-flex justify-content-between flex-grow-1"><div class="me-4 text-wrap"><p>' +
                            v.msg + '</p>';
                        html += '<p class="tx-12 text-muted">' + v.created_at + '</p>';
                        html += '</div></div></a>';
                    });

                    html += '</div>';

                } else {
                    html +=
                        '<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">Tidak Ada Notifikasi</div>';
                }
                html += `<div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                    <a href="{{ route('mapping.notification.index') }}">Lihat Semua</a>
                    </div>`;
                $('#notificationDropdownContent').html(html)
            });
        }
        var password_expire = '{!! request()->get('password_expired') !!}';

        function initMenu() {
            var child = $('a.nav-link.active').parent().addClass('active').parent().addClass('show').parent().parent()
                .parent().parent().addClass('show');
        }

        $(function() {
            initMenu()
            getNotifications()
            initModalAjax('.usermanual');
            initModalAjax('#change-password');
            if (password_expire == '1') {
                $('#change-password').click()
                $($('#change-password').data('bsTarget')).modal('show')
            }

            // const timeout = 3600000; // 3600000 ms = 60 minutes
            const timeout = 60000; // 3600000 ms = 60 minutes
            var idleTimer = null;

            // $('*').bind(
            //     'mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick',
            //     function() {
            //         clearTimeout(idleTimer);

            //         idleTimer = setTimeout(function() {

            //             Swal.fire({
            //                 icon: 'warning',
            //                 title: 'Session telah habis',
            //                 showConfirmButton: true,
            //                 timer: 1500
            //             }).then(function(e){
            //                 document.getElementById('logout-form').submit();
            //             })

            //         }, timeout);
            //     });

            // $("body").trigger("mousemove");
        })
    </script>
@endpush
