<div class="row">
    <!-- Total Reports Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="statistics-title">Total Reports</p>
                        <h3 class="rate-percentage">{{ $totalReports ?? 0 }}</h3>
                        <p class="text-muted text-small">
                            <span class="text-success">
                                <i data-feather="arrow-up" class="icon-sm"></i>
                                {{ $reportGrowth ?? '0%' }}
                            </span>
                            This month
                        </p>
                    </div>
                    <div class="wrapper">
                        <div class="d-flex justify-content-center align-items-center rounded-circle statistics-icon bg-primary">
                            <i data-feather="file-text" class="icon-md text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Reports Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="statistics-title">Completed</p>
                        <h3 class="rate-percentage">{{ $completedReports ?? 0 }}</h3>
                        <p class="text-muted text-small">
                            <span class="text-success">
                                <i data-feather="arrow-up" class="icon-sm"></i>
                                {{ $completionRate ?? '0%' }}
                            </span>
                            Completion rate
                        </p>
                    </div>
                    <div class="wrapper">
                        <div class="d-flex justify-content-center align-items-center rounded-circle statistics-icon bg-success">
                            <i data-feather="check-circle" class="icon-md text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Reports Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="statistics-title">Pending</p>
                        <h3 class="rate-percentage">{{ $pendingReports ?? 0 }}</h3>
                        <p class="text-muted text-small">
                            <span class="text-warning">
                                <i data-feather="clock" class="icon-sm"></i>
                                {{ $pendingRate ?? '0%' }}
                            </span>
                            Awaiting review
                        </p>
                    </div>
                    <div class="wrapper">
                        <div class="d-flex justify-content-center align-items-center rounded-circle statistics-icon bg-warning">
                            <i data-feather="clock" class="icon-md text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Reports Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="statistics-title">Overdue</p>
                        <h3 class="rate-percentage">{{ $overdueReports ?? 0 }}</h3>
                        <p class="text-muted text-small">
                            <span class="text-danger">
                                <i data-feather="arrow-down" class="icon-sm"></i>
                                {{ $overdueRate ?? '0%' }}
                            </span>
                            Past deadline
                        </p>
                    </div>
                    <div class="wrapper">
                        <div class="d-flex justify-content-center align-items-center rounded-circle statistics-icon bg-danger">
                            <i data-feather="alert-triangle" class="icon-md text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Chart Row -->
<div class="row mt-4">
    <div class="col-lg-8 col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Report Progress Overview</h6>
                    <div class="dropdown mb-2">
                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="more-horizontal" class="icon-lg text-muted pb-3px"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i data-feather="eye" class="icon-sm me-2"></i>
                                <span class="">View</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i data-feather="download" class="icon-sm me-2"></i>
                                <span class="">Download</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="reportProgressChart"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Recent Activities</h6>
                <div class="list-group list-group-flush">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $activity['title'] ?? 'Activity' }}</div>
                            <small class="text-muted">{{ $activity['description'] ?? 'No description' }}</small>
                        </div>
                        <small class="text-muted">{{ $activity['time'] ?? 'Just now' }}</small>
                    </div>
                    @empty
                    <div class="list-group-item border-0 px-0">
                        <div class="text-center text-muted">
                            <i data-feather="inbox" class="icon-lg mb-2"></i>
                            <p>No recent activities</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Quick Actions</h6>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <button class="btn btn-primary w-100" type="button">
                            <i data-feather="plus" class="icon-sm me-2"></i>
                            New Report
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <button class="btn btn-outline-secondary w-100" type="button">
                            <i data-feather="eye" class="icon-sm me-2"></i>
                            View All Reports
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <button class="btn btn-outline-secondary w-100" type="button">
                            <i data-feather="download" class="icon-sm me-2"></i>
                            Export Data
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <button class="btn btn-outline-secondary w-100" type="button">
                            <i data-feather="settings" class="icon-sm me-2"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>