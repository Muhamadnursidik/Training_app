@extends('layouts.app')

@section('content')

<page-header title="{{ __('Apps Overview') }}" subtitle="Dashboard"></page-header>

<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg col-md-6 col-sm-6 mb-4">
        <div class="stats-small stats-small--1 card card-small">
            <div class="card-body p-0 d-flex">
                <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                        <span class="stats-small__label text-uppercase">{{ __('Vendor') }}</span>
                        <h6 class="stats-small__value count my-3">2,390</h6>
                    </div>
                    <div class="stats-small__data">
                        <span class="stats-small__percentage stats-small__percentage--increase">4.7%</span>
                    </div>
                </div>
                <canvas height="120" class="blog-overview-stats-small-1"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg col-md-6 col-sm-6 mb-4">
        <div class="stats-small stats-small--1 card card-small">
            <div class="card-body p-0 d-flex">
                <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                        <span class="stats-small__label text-uppercase">{{ __('Reseller') }}</span>
                        <h6 class="stats-small__value count my-3">182</h6>
                    </div>
                    <div class="stats-small__data">
                        <span class="stats-small__percentage stats-small__percentage--increase">12.4%</span>
                    </div>
                </div>
                <canvas height="120" class="blog-overview-stats-small-2"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="stats-small stats-small--1 card card-small">
            <div class="card-body p-0 d-flex">
                <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                        <span class="stats-small__label text-uppercase">{{ __('Product') }}</span>
                        <h6 class="stats-small__value count my-3">8,147</h6>
                    </div>
                    <div class="stats-small__data">
                        <span class="stats-small__percentage stats-small__percentage--decrease">3.8%</span>
                    </div>
                </div>
                <canvas height="120" class="blog-overview-stats-small-3"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="stats-small stats-small--1 card card-small">
            <div class="card-body p-0 d-flex">
                <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                        <span class="stats-small__label text-uppercase">Users</span>
                        <h6 class="stats-small__value count my-3">2,413</h6>
                    </div>
                    <div class="stats-small__data">
                        <span class="stats-small__percentage stats-small__percentage--increase">12.4%</span>
                    </div>
                </div>
                <canvas height="120" class="blog-overview-stats-small-4"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg col-md-4 col-sm-12 mb-4">
        <div class="stats-small stats-small--1 card card-small">
            <div class="card-body p-0 d-flex">
                <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                        <span class="stats-small__label text-uppercase">Subscribers</span>
                        <h6 class="stats-small__value count my-3">17,281</h6>
                    </div>
                    <div class="stats-small__data">
                        <span class="stats-small__percentage stats-small__percentage--decrease">2.4%</span>
                    </div>
                </div>
                <canvas height="120" class="blog-overview-stats-small-5"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- End Small Stats Blocks -->
<div class="row">
    <!-- Users Stats -->
    <div class="col-lg-8 col-md-12 col-sm-12 mb-4">
        <div class="card card-small">
            <div class="card-header border-bottom">
                <h6 class="m-0">Users</h6>
            </div>
            <div class="card-body pt-0">
                <div class="row border-bottom py-2 bg-light">
                    <div class="col-12 col-sm-6">
                        <div id="blog-overview-date-range" class="input-daterange input-group input-group-sm my-auto ml-auto mr-auto ml-sm-auto mr-sm-0" style="max-width: 350px;">
                            <input type="text" class="input-sm form-control" name="start" placeholder="Start Date" id="blog-overview-date-range-1">
                            <input type="text" class="input-sm form-control" name="end" placeholder="End Date" id="blog-overview-date-range-2">
                            <span class="input-group-append">
                                <span class="input-group-text">
                                    <i class="material-icons"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 d-flex mb-2 mb-sm-0">
                        <button type="button" class="btn btn-sm btn-white ml-auto mr-auto ml-sm-auto mr-sm-0 mt-3 mt-sm-0">View Full Report &rarr;</button>
                    </div>
                </div>
                <canvas height="130" style="max-width: 100% !important;" class="blog-overview-users"></canvas>
            </div>
        </div>
    </div>
    <!-- End Users Stats -->
    <!-- Users By Device Stats -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-small h-100">
            <div class="card-header border-bottom">
                <h6 class="m-0">Users by device</h6>
            </div>
            <div class="card-body d-flex py-0">
                <canvas height="220" class="blog-users-by-device m-auto"></canvas>
            </div>
            <div class="card-footer border-top">
                <div class="row">
                    <div class="col">
                        <select class="custom-select custom-select-sm" style="max-width: 130px;">
                            <option selected>Last Week</option>
                            <option value="1">Today</option>
                            <option value="2">Last Month</option>
                            <option value="3">Last Year</option>
                        </select>
                    </div>
                    <div class="col text-right view-report">
                        <a href="#">Full report &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Users By Device Stats -->
    <!-- New Draft Component -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <!-- Quick Post -->
        <div class="card card-small h-100">
            <div class="card-header border-bottom">
                <h6 class="m-0">New Draft</h6>
            </div>
            <div class="card-body d-flex flex-column">
                <form class="quick-post-form">
                    <div class="form-group">
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Brave New World"> </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Words can be like X-rays if you use them properly..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-accent">Create Draft</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Quick Post -->
    </div>
    <!-- End New Draft Component -->
    <!-- Discussions Component -->
    <div class="col-lg-5 col-md-12 col-sm-12 mb-4">
        <div class="card card-small blog-comments">
            <div class="card-header border-bottom">
                <h6 class="m-0">Discussions</h6>
            </div>
            <div class="card-body p-0">
                <div class="blog-comments__item d-flex p-3">
                    <div class="blog-comments__avatar mr-3">
                        <img src="/images/avatars/1.jpg" alt="User avatar" /> </div>
                    <div class="blog-comments__content">
                        <div class="blog-comments__meta text-muted">
                            <a class="text-secondary" href="#">James Johnson</a> on
                            <a class="text-secondary" href="#">Hello World!</a>
                            <span class="text-muted">– 3 days ago</span>
                        </div>
                        <p class="m-0 my-1 mb-2 text-muted">Well, the way they make shows is, they make one show ...</p>
                        <div class="blog-comments__actions">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-white">
                                    <span class="text-success">
                                        <i class="material-icons">check</i>
                                    </span> Approve </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-danger">
                                        <i class="material-icons">clear</i>
                                    </span> Reject </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-light">
                                        <i class="material-icons">more_vert</i>
                                    </span> Edit </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="blog-comments__item d-flex p-3">
                    <div class="blog-comments__avatar mr-3">
                        <img src="/images/avatars/2.jpg" alt="User avatar" /> </div>
                    <div class="blog-comments__content">
                        <div class="blog-comments__meta text-muted">
                            <a class="text-secondary" href="#">James Johnson</a> on
                            <a class="text-secondary" href="#">Hello World!</a>
                            <span class="text-muted">– 4 days ago</span>
                        </div>
                        <p class="m-0 my-1 mb-2 text-muted">After the avalanche, it took us a week to climb out. Now...</p>
                        <div class="blog-comments__actions">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-white">
                                    <span class="text-success">
                                        <i class="material-icons">check</i>
                                    </span> Approve </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-danger">
                                        <i class="material-icons">clear</i>
                                    </span> Reject </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-light">
                                        <i class="material-icons">more_vert</i>
                                    </span> Edit </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="blog-comments__item d-flex p-3">
                    <div class="blog-comments__avatar mr-3">
                        <img src="/images/avatars/3.jpg" alt="User avatar" /> </div>
                    <div class="blog-comments__content">
                        <div class="blog-comments__meta text-muted">
                            <a class="text-secondary" href="#">James Johnson</a> on
                            <a class="text-secondary" href="#">Hello World!</a>
                            <span class="text-muted">– 5 days ago</span>
                        </div>
                        <p class="m-0 my-1 mb-2 text-muted">My money's in that office, right? If she start giving me...</p>
                        <div class="blog-comments__actions">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-white">
                                    <span class="text-success">
                                        <i class="material-icons">check</i>
                                    </span> Approve </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-danger">
                                        <i class="material-icons">clear</i>
                                    </span> Reject </button>
                                <button type="button" class="btn btn-white">
                                    <span class="text-light">
                                        <i class="material-icons">more_vert</i>
                                    </span> Edit </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="row">
                    <div class="col text-center view-report">
                        <button type="submit" class="btn btn-white">View All Comments</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Discussions Component -->
    <!-- Top Referrals Component -->
    <div class="col-lg-3 col-md-12 col-sm-12 mb-4">
        <div class="card card-small">
            <div class="card-header border-bottom">
                <h6 class="m-0">Top Referrals</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-small list-group-flush">
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">GitHub</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">19,291</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">Stack Overflow</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">11,201</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">Hacker News</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">9,291</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">Reddit</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">8,281</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">The Next Web</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">7,128</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">Tech Crunch</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">6,218</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">YouTube</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">1,218</span>
                    </li>
                    <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">Adobe</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">827</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer border-top">
                <div class="row">
                    <div class="col">
                        <select class="custom-select custom-select-sm">
                            <option selected>Last Week</option>
                            <option value="1">Today</option>
                            <option value="2">Last Month</option>
                            <option value="3">Last Year</option>
                        </select>
                    </div>
                    <div class="col text-right view-report">
                        <a href="#">Full report &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Top Referrals Component -->
</div>

@endsection


@push('js')
<script type="text/javascript">
$(function () {

    // Blog overview date range init.
    $('#blog-overview-date-range').datepicker({});

    //
    // Small Stats
    //

    // Datasets
    var boSmallStatsDatasets = [
      {
        backgroundColor: 'rgba(0, 184, 216, 0.1)',
        borderColor: 'rgb(0, 184, 216)',
        data: [1, 2, 1, 3, 5, 4, 7],
      },
      {
        backgroundColor: 'rgba(23,198,113,0.1)',
        borderColor: 'rgb(23,198,113)',
        data: [1, 2, 3, 3, 3, 4, 4]
      },
      {
        backgroundColor: 'rgba(255,180,0,0.1)',
        borderColor: 'rgb(255,180,0)',
        data: [2, 3, 3, 3, 4, 3, 3]
      },
      {
        backgroundColor: 'rgba(255,65,105,0.1)',
        borderColor: 'rgb(255,65,105)',
        data: [1, 7, 1, 3, 1, 4, 8]
      },
      {
        backgroundColor: 'rgb(0,123,255,0.1)',
        borderColor: 'rgb(0,123,255)',
        data: [3, 2, 3, 2, 4, 5, 4]
      }
    ];

    // Options
    function boSmallStatsOptions(max) {
      return {
        maintainAspectRatio: true,
        responsive: true,
        // Uncomment the following line in order to disable the animations.
        // animation: false,
        legend: {
          display: false
        },
        tooltips: {
          enabled: false,
          custom: false
        },
        elements: {
          point: {
            radius: 0
          },
          line: {
            tension: 0.3
          }
        },
        scales: {
          xAxes: [{
            gridLines: false,
            scaleLabel: false,
            ticks: {
              display: false
            }
          }],
          yAxes: [{
            gridLines: false,
            scaleLabel: false,
            ticks: {
              display: false,
              // Avoid getting the graph line cut of at the top of the canvas.
              // Chart.js bug link: https://github.com/chartjs/Chart.js/issues/4790
              suggestedMax: max
            }
          }],
        },
      };
    }

    // Generate the small charts
    boSmallStatsDatasets.map(function (el, index) {
      var chartOptions = boSmallStatsOptions(Math.max.apply(Math, el.data) + 1);
      var ctx = document.getElementsByClassName('blog-overview-stats-small-' + (index + 1));
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5", "Label 6", "Label 7"],
          datasets: [{
            label: 'Today',
            fill: 'start',
            data: el.data,
            backgroundColor: el.backgroundColor,
            borderColor: el.borderColor,
            borderWidth: 1.5,
          }]
        },
        options: chartOptions
      });
    });


    //
    // Blog Overview Users
    //

    var bouCtx = document.getElementsByClassName('blog-overview-users')[0];

    // Data
    var bouData = {
      // Generate the days labels on the X axis.
      labels: Array.from(new Array(30), function (_, i) {
        return i === 0 ? 1 : i;
      }),
      datasets: [{
        label: 'Current Month',
        fill: 'start',
        data: [500, 800, 320, 180, 240, 320, 230, 650, 590, 1200, 750, 940, 1420, 1200, 960, 1450, 1820, 2800, 2102, 1920, 3920, 3202, 3140, 2800, 3200, 3200, 3400, 2910, 3100, 4250],
        backgroundColor: 'rgba(0,123,255,0.1)',
        borderColor: 'rgba(0,123,255,1)',
        pointBackgroundColor: '#ffffff',
        pointHoverBackgroundColor: 'rgb(0,123,255)',
        borderWidth: 1.5,
        pointRadius: 0,
        pointHoverRadius: 3
      }, {
        label: 'Past Month',
        fill: 'start',
        data: [380, 430, 120, 230, 410, 740, 472, 219, 391, 229, 400, 203, 301, 380, 291, 620, 700, 300, 630, 402, 320, 380, 289, 410, 300, 530, 630, 720, 780, 1200],
        backgroundColor: 'rgba(255,65,105,0.1)',
        borderColor: 'rgba(255,65,105,1)',
        pointBackgroundColor: '#ffffff',
        pointHoverBackgroundColor: 'rgba(255,65,105,1)',
        borderDash: [3, 3],
        borderWidth: 1,
        pointRadius: 0,
        pointHoverRadius: 2,
        pointBorderColor: 'rgba(255,65,105,1)'
      }]
    };

    // Options
    var bouOptions = {
      responsive: true,
      legend: {
        position: 'top'
      },
      elements: {
        line: {
          // A higher value makes the line look skewed at this ratio.
          tension: 0.3
        },
        point: {
          radius: 0
        }
      },
      scales: {
        xAxes: [{
          gridLines: false,
          ticks: {
            callback: function (tick, index) {
              // Jump every 7 values on the X axis labels to avoid clutter.
              return index % 7 !== 0 ? '' : tick;
            }
          }
        }],
        yAxes: [{
          ticks: {
            suggestedMax: 45,
            callback: function (tick, index, ticks) {
              if (tick === 0) {
                return tick;
              }
              // Format the amounts using Ks for thousands.
              return tick > 999 ? (tick/ 1000).toFixed(1) + 'K' : tick;
            }
          }
        }]
      },
      // Uncomment the next lines in order to disable the animations.
      // animation: {
      //   duration: 0
      // },
      hover: {
        mode: 'nearest',
        intersect: false
      },
      tooltips: {
        custom: false,
        mode: 'nearest',
        intersect: false
      }
    };

    // Generate the Analytics Overview chart.
    window.BlogOverviewUsers = new Chart(bouCtx, {
      type: 'LineWithLine',
      data: bouData,
      options: bouOptions
    });

    // Hide initially the first and last analytics overview chart points.
    // They can still be triggered on hover.
    var aocMeta = BlogOverviewUsers.getDatasetMeta(0);
    aocMeta.data[0]._model.radius = 0;
    aocMeta.data[bouData.datasets[0].data.length - 1]._model.radius = 0;

    // Render the chart.
    window.BlogOverviewUsers.render();

    //
    // Users by device pie chart
    //

    // Data
    var ubdData = {
      datasets: [{
        hoverBorderColor: '#ffffff',
        data: [68.3, 24.2, 7.5],
        backgroundColor: [
          'rgba(0,123,255,0.9)',
          'rgba(0,123,255,0.5)',
          'rgba(0,123,255,0.3)'
        ]
      }],
      labels: ["Desktop", "Tablet", "Mobile"]
    };

    // Options
    var ubdOptions = {
      legend: {
        position: 'bottom',
        labels: {
          padding: 25,
          boxWidth: 20
        }
      },
      cutoutPercentage: 0,
      // Uncomment the following line in order to disable the animations.
      // animation: false,
      tooltips: {
        custom: false,
        mode: 'index',
        position: 'nearest'
      }
    };

    var ubdCtx = document.getElementsByClassName('blog-users-by-device')[0];

    // Generate the users by device chart.
    window.ubdChart = new Chart(ubdCtx, {
      type: 'pie',
      data: ubdData,
      options: ubdOptions
    });

});
</script>
@endpush
