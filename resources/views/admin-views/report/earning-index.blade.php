@extends('layouts.admin.app')

@section('title', translate('Earning Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/earning_report.png')}}" alt="">
                {{\App\CentralLogics\translate('earning_Report')}}
            </h2>
        </div>

        <div class="card card-body mb-3">
            <div class="media gap-3 flex-column flex-sm-row align-items-sm-center">
                <!-- Avatar -->
                <div class="avatar avatar-xl avatar-4by3">
                    <img class="avatar-img" src="{{asset('public/assets/admin')}}/svg/illustrations/earnings.png"
                         alt="Image Description">
                </div>
                <!-- End Avatar -->

                <div class="media-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div class="text-capitalize">
                            <h2 class="page-header-title">{{\App\CentralLogics\translate('earning')}} {{\App\CentralLogics\translate('report')}} {{\App\CentralLogics\translate('overview')}}</h2>

                            <div class="meida flex-column gap-3">
                                <div class="">
                                    <span>{{\App\CentralLogics\translate('admin')}}:</span>
                                    <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                                </div>

                                <div class="media-body">
                                    <div class="d-flex align-items-center text-nowrap gap-2">
                                        <div class="">{{\App\CentralLogics\translate('date')}}:</div>
                                        <div>( {{session('from_date')}} - {{session('to_date')}} )</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a class="btn btn-icon btn-primary rounded-circle" href="{{route('admin.dashboard')}}">
                            <i class="tio-home-outlined"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.report.set-date')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="exampleInputEmail1" class="form-label">{{\App\CentralLogics\translate('show')}} {{\App\CentralLogics\translate('data')}} {{\App\CentralLogics\translate('by')}} {{\App\CentralLogics\translate('date')}}
                                    {{\App\CentralLogics\translate('range')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <input type="date" name="from" id="from_date"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <input type="date" name="to" id="to_date"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-block">{{\App\CentralLogics\translate('show')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @php
                $from = session('from_date');
               $to = session('to_date');
               $total_tax=\App\Model\Order::where(['order_status'=>'delivered'])
               ->whereBetween('created_at', [$from, $to])
               ->sum('total_tax_amount');
               if($total_tax==0){
                   $total_tax=0.01;
               }
            @endphp
            <div class="col-sm-6 mb-3">
                @php
                    $total_sold=\App\Model\Order::where(['order_status'=>'delivered'])
                    ->whereBetween('created_at', [$from, $to])->sum('order_amount');
                if($total_sold==0){
                    $total_sold=.01;
                }
                @endphp
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <!-- Media -->
                                <div class="media">
                                    <i class="tio-dollar-outlined nav-icon"></i>

                                    <div class="media-body">
                                        <h4 class="mb-1">{{\App\CentralLogics\translate('total')}} {{\App\CentralLogics\translate('sold')}}</h4>
                                        <span class="font-size-sm text-success">
                                          <i class="tio-trending-up"></i> {{ Helpers::set_symbol(round(abs($total_sold-$total_tax))) }}
                                        </span>
                                    </div>

                                </div>
                                <!-- End Media -->
                            </div>

                            <div class="col-auto">
                                <!-- Circle -->
                                <div class="js-circle"
                                     data-hs-circles-options='{
                                       "value": {{$total_sold=='.01'?0:round((($total_sold-$total_tax)/$total_sold)*100)}},
                                       "maxValue": 100,
                                       "duration": 2000,
                                       "isViewportInit": true,
                                       "colors": ["#e7eaf3", "green"],
                                       "radius": 25,
                                       "width": 3,
                                       "fgStrokeLinecap": "round",
                                       "textFontSize": 14,
                                       "additionalText": "%",
                                       "textClass": "circle-custom-text",
                                       "textColor": "green"
                                     }'></div>
                                <!-- End Circle -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-6 mb-3">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <!-- Media -->
                                <div class="media">
                                    <i class="tio-money nav-icon"></i>

                                    <div class="media-body">
                                        <h4 class="mb-1">{{\App\CentralLogics\translate('total')}} {{\App\CentralLogics\translate('tax')}}</h4>
                                        <span class="font-size-sm text-warning">
                                          <i class="tio-trending-up"></i> {{ Helpers::set_symbol($total_tax) }}
                                        </span>
                                    </div>
                                </div>
                                <!-- End Media -->
                            </div>

                            <div class="col-auto">
                                <!-- Circle -->
                                <div class="js-circle"
                                     data-hs-circles-options='{
                           "value": {{$total_tax=='0.01'?0:round(((abs($total_tax))/$total_sold)*100)}},
                           "maxValue": 100,
                           "duration": 2000,
                           "isViewportInit": true,
                           "colors": ["#e7eaf3", "#ec9a3c"],
                           "radius": 25,
                           "width": 3,
                           "fgStrokeLinecap": "round",
                           "textFontSize": 14,
                           "additionalText": "%",
                           "textClass": "circle-custom-text",
                           "textColor": "#ec9a3c"
                         }'></div>
                                <!-- End Circle -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                @php
                    $total_sold=\App\Model\Order::where(['order_status'=>'delivered'])->whereBetween('created_at', [date('y-01-01'), date('y-12-31')])->sum('order_amount')
                @endphp
                <h6 class="card-subtitle mb-0">{{translate('Total sale of')}} {{date('Y')}} :<span
                        class="h3 ml-sm-2"> {{ Helpers::set_symbol($total_sold) }}</span>
                </h6>

                <!-- Unfold -->
                <div class="hs-unfold">
                    <a class="js-hs-unfold-invoker btn btn-white d-flex gap-1 align-items-center"
                       href="{{route('admin.order.list',['status'=>'all'])}}">
                        <i class="tio-shopping-cart-outlined"></i> {{\App\CentralLogics\translate('orders')}}
                    </a>
                </div>
                <!-- End Unfold -->
            </div>
            <!-- End Header -->

            @php
                $sold=[];
                    for ($i=1;$i<=12;$i++){
                        $from = date('Y-'.$i.'-01');
                        $to = date('Y-'.$i.'-30');
                        $sold[$i]=\App\Model\Order::where(['order_status'=>'delivered'])->whereBetween('created_at', [$from, $to])->sum('order_amount');
                    }
            @endphp

            @php
                $tax=[];
                    for ($i=1;$i<=12;$i++){
                        $from = date('Y-'.$i.'-01');
                        $to = date('Y-'.$i.'-30');
                        $tax[$i]=\App\Model\Order::where(['order_status'=>'delivered'])->whereBetween('created_at', [$from, $to])->sum('total_tax_amount');
                    }
            @endphp

            <div class="card-body">
                <!-- Bar Chart -->
                <div class="chartjs-custom" style="height: 18rem;">
                    <canvas class="js-chart"
                            data-hs-chartjs-options='{
                        "type": "line",
                        "data": {
                           "labels": ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                           "datasets": [{
                            "data": [{{$sold[1]}},{{$sold[2]}},{{$sold[3]}},{{$sold[4]}},{{$sold[5]}},{{$sold[6]}},{{$sold[7]}},{{$sold[8]}},{{$sold[9]}},{{$sold[10]}},{{$sold[11]}},{{$sold[12]}}],
                            "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "green",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "green",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#377dff"
                          },
                          {
                            "data": [{{$tax[1]}},{{$tax[2]}},{{$tax[3]}},{{$tax[4]}},{{$tax[5]}},{{$tax[6]}},{{$tax[7]}},{{$tax[8]}},{{$tax[9]}},{{$tax[10]}},{{$tax[11]}},{{$tax[12]}}],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#ec9a3c",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#ec9a3c",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                          }]
                        },
                        "options": {
                          "gradientPosition": {"y1": 200},
                           "scales": {
                              "yAxes": [{
                                "gridLines": {
                                  "color": "#e7eaf3",
                                  "drawBorder": false,
                                  "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                  "min": 0,
                                  "max": {{\App\CentralLogics\Helpers::max_earning()}},
                                  "stepSize": {{round(\App\CentralLogics\Helpers::max_earning()/5)}},
                                  "fontColor": "#97a4af",
                                  "fontFamily": "Open Sans, sans-serif",
                                  "padding": 10,
                                  "postfix": " {{\App\CentralLogics\Helpers::currency_symbol()}}"
                                }
                              }],
                              "xAxes": [{
                                "gridLines": {
                                  "display": false,
                                  "drawBorder": false
                                },
                                "ticks": {
                                  "fontSize": 12,
                                  "fontColor": "#97a4af",
                                  "fontFamily": "Open Sans, sans-serif",
                                  "padding": 5
                                }
                              }]
                          },
                          "tooltips": {
                            "prefix": "",
                            "postfix": "",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false,
                            "lineMode": true,
                            "lineWithLineColor": "rgba(19, 33, 68, 0.075)"
                          },
                          "hover": {
                            "mode": "nearest",
                            "intersect": true
                          }
                        }
                      }'>
                    </canvas>
                </div>
                <!-- End Bar Chart -->
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush

@push('script_2')

    <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script
        src="{{asset('public/assets/admin')}}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/js/hs.chartjs-matrix.js"></script>

    <script>
        $(document).on('ready', function () {

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function (e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function (dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function () {
                                return '';
                            },
                            label: function (item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() + ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function () {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{ translate("Invalid date range!") }}', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
@endpush
