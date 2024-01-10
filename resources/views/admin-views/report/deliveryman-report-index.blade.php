@extends('layouts.admin.app')

@section('title', translate('deliveryman_report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/order_report.png')}}" alt="">
                {{\App\CentralLogics\translate('deliveryman_report')}}
            </h2>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="media gap-3 flex-column flex-sm-row align-items-sm-center">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl avatar-4by3 mr-2">
                        <img class="avatar-img" src="{{asset('public/assets/admin')}}/svg/illustrations/order.png"
                                alt="Image Description">
                    </div>
                    <!-- End Avatar -->

                    <div class="media-body">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <div class="">
                                <h2 class="page-header-title">{{ \App\CentralLogics\translate('Deliveryman Report Overview') }}</h2>

                                <div class="meida flex-column gap-3">
                                    <span>{{\App\CentralLogics\translate('admin')}}:</span>
                                    <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                                </div>
                            </div>

                            <a class="btn btn-icon btn-primary rounded-circle" href="{{route('admin.dashboard')}}">
                                <i class="tio-home-outlined"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <!-- Header -->
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">{{\App\CentralLogics\translate('show')}} {{\App\CentralLogics\translate('data')}}
                                    {{\App\CentralLogics\translate('by')}} {{\App\CentralLogics\translate('date')}}
                                    {{\App\CentralLogics\translate('range')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="mb-3">
                                <select class="form-control" name="delivery_man_id" id="delivery_man">
                                    <option value="all">{{\App\CentralLogics\translate('all')}} {{\App\CentralLogics\translate('deliveryman')}}</option>
                                    @foreach($delivery_men as $deliveryMan)
                                        <option value="{{$deliveryMan['id']}}" {{ $deliveryman_id == $deliveryMan['id'] ? 'selected' : ''}}>{{$deliveryMan['f_name'].' '.$deliveryMan['l_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="mb-3">
                                <input type="date" name="start_date" id="from_date" value="{{ $start_date }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="mb-3">
                                <input type="date" name="end_date" id="to_date" value="{{ $end_date }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3 mb-3 __btn-row">
                            <a href="{{ route('admin.report.driver-report') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                            <button type="submit" class="btn btn-primary btn-block">{{\App\CentralLogics\translate('show')}}</button>
                        </div>
                    </div>
                </form>
                <div class="">
                    <strong>
                        {{\App\CentralLogics\translate('total')}}  {{\App\CentralLogics\translate('delivered')}} {{\App\CentralLogics\translate('qty')}} :
                        <span>{{$orders->total()}}</span>
                    </strong>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        style="width: 100%">
                    <thead class="thead-light">
                    <tr>
                        <th class="">
                            {{\App\CentralLogics\translate('SL')}}
                        </th>
                        <th class="table-column-pl-0">{{\App\CentralLogics\translate('order')}}</th>
                        <th>{{\App\CentralLogics\translate('date')}}</th>
                        <th>{{\App\CentralLogics\translate('customer')}}</th>
                        <th>{{\App\CentralLogics\translate('branch')}}</th>
                         <th>{{\App\CentralLogics\translate('payment')}} {{\App\CentralLogics\translate('status')}}</th>
                        <th>{{\App\CentralLogics\translate('total')}}</th>
                        <th>{{\App\CentralLogics\translate('order')}} {{\App\CentralLogics\translate('status')}}</th>
                        <th>{{\App\CentralLogics\translate('actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$key+1}}
                            </td>
                            <td class="table-column-pl-0">
                                <a href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                            <td>
                                @if($order->customer)
                                    <a class="text-body text-capitalize"
                                       href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</a>
                                @else
                                    <label class="badge badge-danger">{{\App\CentralLogics\translate('invalid')}} {{\App\CentralLogics\translate('customer')}} {{\App\CentralLogics\translate('data')}}</label>
                                @endif
                            </td>
                            <td>
                                <label class="badge badge-soft-primary">{{$order->branch?$order->branch->name:'Branch deleted!'}}</label>
                            </td>
                            <td>
                                @if($order->payment_status=='paid')
                                    <span class="badge badge-soft-success">
                                      <span class="legend-indicator bg-success"></span>{{\App\CentralLogics\translate('paid')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger">
                                      <span class="legend-indicator bg-danger"></span>{{\App\CentralLogics\translate('unpaid')}}
                                    </span>
                                @endif
                            </td>
                            <td>{{ Helpers::set_symbol($order['order_amount']) }}</td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-info"></span>{{\App\CentralLogics\translate('pending')}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-info"></span>{{\App\CentralLogics\translate('confirmed')}}
                                    </span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-warning"></span>{{\App\CentralLogics\translate('processing')}}
                                    </span>
                                @elseif($order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-warning"></span>{{\App\CentralLogics\translate('out_for_delivery')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-success"></span>{{\App\CentralLogics\translate('delivered')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                      <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['order_status'])}}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        <i class="tio-settings"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                           href="{{route('admin.orders.details',['id'=>$order['id']])}}"><i
                                                class="tio-visible"></i> {{\App\CentralLogics\translate('view')}}</a>
                                        <a class="dropdown-item" target="_blank"
                                           href="{{route('admin.orders.generate-invoice',[$order['id']])}}"><i
                                                class="tio-download"></i> {{\App\CentralLogics\translate('invoice')}}</a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    <!-- Pagination -->
                    {!! $orders->links() !!}
                </div>
            </div>
            @if(count($orders)==0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
            <!-- End Table -->
        </div>
        @endsection

        @push('script_2')

            <script>
                $(document).on('ready', function () {

                    // INITIALIZATION OF FLATPICKR
                    // =======================================================
                    $('.js-flatpickr').each(function () {
                        $.HSCore.components.HSFlatpickr.init($(this));
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
