@extends('layouts.admin.app')

@section('title', translate('Product Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="media mb-3">
                <!-- Avatar -->
                <div class="avatar avatar-xl avatar-4by3 mr-2">
                    <img class="avatar-img" src="{{asset('public/assets/admin')}}/svg/illustrations/order.png"
                         alt="Image Description">
                </div>
                <!-- End Avatar -->

                <div class="media-body">
                    <div class="row">
                        <div class="col-lg mb-3 mb-lg-0">
                            <h1 class="page-header-title">{{\App\CentralLogics\translate('product')}} {{\App\CentralLogics\translate('report')}} {{\App\CentralLogics\translate('overview')}}</h1>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span>{{\App\CentralLogics\translate('admin')}}:</span>
                                    <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-auto">
                            <div class="d-flex">
                                <a class="btn btn-icon btn-primary rounded-circle" href="{{route('admin.dashboard')}}">
                                    <i class="tio-home-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Media -->

            <!-- Nav -->
            <!-- Nav -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev" style="display: none;">
                  <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                  </a>
                </span>

                <span class="hs-nav-scroller-arrow-next" style="display: none;">
                  <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                  </a>
                </span>

                <ul class="nav nav-tabs page-header-tabs" id="projectsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:">{{\App\CentralLogics\translate('overview')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav -->
        </div>
        <!-- End Page Header -->

        <div>
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <div class="col-lg-12 pt-3">
                        <form action="#" id="form-data" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-xl-2 mb-3">
                                    <select class="custom-select custom-select" name="branch_id" id="branch_id">
                                    <option value="all">{{\App\CentralLogics\translate('all')}} {{\App\CentralLogics\translate('branch')}}</option>
                                    @foreach($branches as $branch)
                                            <option value="{{$branch['id']}}" {{ $branch_id==$branch['id']?'selected':''}}>{{$branch['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6 col-xl-3 mb-3">
                                    <select class="form-control js-select2-custom" name="product_id" id="product_id" required>
                                        <option value="0">{{\App\CentralLogics\translate('select')}} {{\App\CentralLogics\translate('product')}}</option>
                                        @foreach($products as $product)
                                            <option value="{{$product['id']}}" {{ $product_id==$product['id']?'selected':''}}>{{$product['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6 col-xl-2 mb-3">
                                    <input type="date" name="start_date" id="from_date" value="{{ $start_date }}" class="form-control">
                                </div>
                                <div class="col-sm-6 col-xl-2 mb-3">
                                    <input type="date" name="end_date" id="to_date" value="{{ $end_date }}" class="form-control">
                                </div>
                                <div class="col-sm-6 col-xl-3 mb-3 __btn-row">
                                    <a href="{{ route('admin.report.product-report') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                    <button type="submit" class="btn btn-primary btn-block">{{\App\CentralLogics\translate('show')}}</button>
                                </div>

                                <div class="col-md-6 pt-4">
                                    <strong>{{\App\CentralLogics\translate('total')}} {{\App\CentralLogics\translate('orders')}} :
                                        <span id=""> {{ count($data) }}</span>
                                    </strong><br>
                                    <strong>
                                        {{\App\CentralLogics\translate('total')}} {{\App\CentralLogics\translate('item')}} {{\App\CentralLogics\translate('qty')}} :
                                        <span id="">{{ $total_qty }}</span>
                                    </strong><br>
                                    <strong>{{\App\CentralLogics\translate('total')}}  {{\App\CentralLogics\translate('amount')}} :
                                        <span id="">{{ Helpers::set_symbol($total_sold) }}</span>
                                    </strong>
                                </div>
                                <div class="col-6 pt-4">
                                    <!-- Unfold -->
                                    <div class="hs-unfold mr-5 float-right">
                                        <a class="js-hs-unfold-invoker btn btn-sm btn-white"
                                           href="{{route('admin.report.export-product-report', ['branch_id'=>Request::get('branch_id'), 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date'), 'product_id'=>Request::get('product_id')])}}">
                                            <i class="tio-download-to mr-1"></i> {{\App\CentralLogics\translate('export')}}
                                        </a>
                                    </div>
                                    <!-- End Unfold -->
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- End Header -->

                <!-- Table -->
                <div class="table">
                    <div class="row">
                        <div class="col-12 pr-4 pl-4">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{\App\CentralLogics\translate('#')}} </th>
                                    <th>{{\App\CentralLogics\translate('order')}}</th>
                                    <th>{{\App\CentralLogics\translate('date')}}</th>
                                    <th>{{\App\CentralLogics\translate('qty')}}</th>
                                    <th style="width: 10%">{{\App\CentralLogics\translate('amount')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key=>$row)
                                    <tr>
                                        <td class="">
                                            {{$key+1}}
                                        </td>
                                        <td class="">
                                            <a href="{{route('admin.orders.details',['id'=>$row['order_id']])}}">{{$row['order_id']}}</a>
                                        </td>
                                        <td>{{date('d M Y',strtotime($row['date']))}}</td>
                                        <td>{{$row['quantity']}}</td>
                                        <td>{{ Helpers::set_symbol($row['price']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($data)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Table -->
            </div>
            <!-- End Row -->
        </div>
        @endsection

        @push('script_2')
            <script>
                $(document).on('ready', function () {
                    // INITIALIZATION OF NAV SCROLLER
                    // =======================================================
                    $('.js-nav-scroller').each(function () {
                        new HsNavScroller($(this)).init()
                    });

                    // INITIALIZATION OF SELECT2
                    // =======================================================
                    $('.js-select2-custom').each(function () {
                        var select2 = $.HSCore.components.HSSelect2.init($(this));
                    });


                    // INITIALIZATION OF DATATABLES
                    // =======================================================
                    var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'copy',
                                className: 'd-none'
                            },
                            {
                                extend: 'excel',
                                className: 'd-none'
                            },
                            {
                                extend: 'csv',
                                className: 'd-none'
                            },
                            {
                                extend: 'pdf',
                                className: 'd-none'
                            },
                            {
                                extend: 'print',
                                className: 'd-none'
                            },
                        ],
                        select: {
                            style: 'multi',
                            selector: 'td:first-child input[type="checkbox"]',
                            classMap: {
                                checkAll: '#datatableCheckAll',
                                counter: '#datatableCounter',
                                counterInfo: '#datatableCounterInfo'
                            }
                        },
                        language: {
                            zeroRecords: '<div class="text-center p-4">' +
                                '<img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                                '<p class="mb-0">{{translate('No data to show')}}</p>' +
                                '</div>'
                        }
                    });

                    // INITIALIZATION OF TAGIFY
                    // =======================================================
                    $('.js-tagify').each(function () {
                        var tagify = $.HSCore.components.HSTagify.init($(this));
                    });
                });

                function filter_branch_orders(id) {
                    location.href = '{{url('/')}}/admin/orders/branch-filter/' + id;
                }
            </script>

            <script>
                $('#search-form').on('submit', function () {
                    $.post({
                        url: "{{route('admin.report.product-report-filter')}}",
                        data: $('#search-form').serialize(),

                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            $('#order_count').html(data.order_count);
                            $('#order_amount').html(data.order_sum);
                            $('#item_count').html(data.item_qty);
                            $('#set-rows').html(data.view);
                            $('.card-footer').hide();
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
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
                            toastr.error('Invalid date range!', Error, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    }
                });
            </script>
    @endpush
