@extends('layouts.branch.app')

@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img src="{{asset('public/assets/admin/img/icons/all_orders.png')}}" alt="">
                {{\App\CentralLogics\translate('all_orders')}}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{$orders->total()}}</span>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="#" id="form-data" method="GET">
                        <div class="row align-items-end gy-3 gx-2">
                            <div class="col-12 pb-0">
                                <h4>{{\App\CentralLogics\translate('Select_Date_Range')}}</h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label for="filter">{{\App\CentralLogics\translate('Select_Orders')}}</label>
                                <select class="custom-select custom-select-sm" name="branch"
                                        onchange="filter_branch_orders(this.value)">
                                    <option disabled>--- {{\App\CentralLogics\translate('select')}} {{\App\CentralLogics\translate('branch')}} ---</option>
                                    <option value="0" {{session('branch_filter')==0?'selected':''}}>{{\App\CentralLogics\translate('all')}} {{\App\CentralLogics\translate('branch')}}</option>
                                    @foreach(\App\Model\Branch::all() as $branch)
                                        <option value="{{$branch['id']}}" {{session('branch_filter')==$branch['id']?'selected':''}}>{{$branch['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="">
                                    <label for="form_date">{{\App\CentralLogics\translate('Start_Date')}}</label>
                                    <input type="date" name="from" value="" id="from_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0">
                                <div class="">
                                    <label for="to_date">{{\App\CentralLogics\translate('End_date')}}</label>
                                    <input type="date" value="" name="to" id="to_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0 __btn-row">
                                <a href="{{ route('branch.orders.list',[$status]) }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                <button type="submit" class="btn btn-primary btn-block">{{\App\CentralLogics\translate('Show_Data')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-3">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                       class="form-control"
                                       placeholder="{{translate('Search by order ID')}}" aria-label="Search"
                                       value="{{$search}}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="false">
                                <i class="tio-download-to"></i>
                                Export
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right w-auto">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{route('branch.orders.export', [$status, 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date'), 'search'=>Request::get('search')])}}">
                                        <img width="14" src="{{asset('public/assets/admin/img/icons/excel.png')}}" alt="">
                                        {{\App\CentralLogics\translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-dark">
                    <thead class="thead-light">
                    <tr>
                        <th class="">
                            {{\App\CentralLogics\translate('SL')}}
                        </th>
                        <th>{{\App\CentralLogics\translate('order_ID')}}</th>
                        <th>{{\App\CentralLogics\translate('order_date')}}</th>
                        <th>{{\App\CentralLogics\translate('customer_Info')}}</th>
                        <th>{{\App\CentralLogics\translate('total_amount')}}</th>
                        <th>{{\App\CentralLogics\translate('order')}} {{\App\CentralLogics\translate('status')}}</th>
                        <th>{{\App\CentralLogics\translate('order')}} {{\App\CentralLogics\translate('type')}}</th>
                        <th class="text-center">{{\App\CentralLogics\translate('actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$orders->firstitem()+$key}}
                            </td>
                            <td>
                                <a class="text-dark" href="{{route('branch.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>
                                <div>{{date('d M Y',strtotime($order['created_at']))}}</div>
                                <div class="fs-12">{{date("h:i A",strtotime($order['created_at']))}}</div>
                            </td>
                            <td>
                                @if($order->customer)
                                    <a class="text-capitalize" href="{{route('branch.orders.details',['id'=>$order['id']])}}">
                                        <h6 class="mb-0">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</h6>
                                    </a>
                                    <a class="text-dark fs-12" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                @else
                                    <h6 class="text-muted text-capitalize">{{\App\CentralLogics\translate('customer')}} {{\App\CentralLogics\translate('deleted')}}</h6>
                                @endif
                            </td>
                            <td>
                                <div>{{ Helpers::set_symbol($order['order_amount']) }}</div>
                                @if($order->payment_status=='paid')
                                    <span class="text-success">{{\App\CentralLogics\translate('paid')}}</span>
                                @else
                                    <span class="text-danger">{{\App\CentralLogics\translate('unpaid')}}</span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info">{{\App\CentralLogics\translate('pending')}}</span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info">{{\App\CentralLogics\translate('confirmed')}}</span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning">{{\App\CentralLogics\translate('processing')}}</span>
                                @elseif($order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning">{{\App\CentralLogics\translate('out_for_delivery')}}</span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success">{{\App\CentralLogics\translate('delivered')}}</span>
                                @else
                                    <span class="badge badge-soft-danger">{{str_replace('_',' ',$order['order_status'])}}</span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                @if($order['order_type']=='self_pickup')
                                    <span class="badge badge-soft-primary">
                                            {{translate('self_pickup')}}
                                        </span>
                                @elseif($order['order_type']=='pos')
                                    <span class="badge badge-soft-info">
                                        {{translate('POS')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-success">
                                        {{translate($order['order_type'])}}
                                        </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a class="btn btn-outline-primary square-btn"
                                        href="{{route('branch.orders.details',['id'=>$order['id']])}}"><i
                                            class="tio-visible"></i></a>
                                    <a class="btn btn-outline-info square-btn" target="_blank"
                                        href="{{route('branch.orders.generate-invoice',[$order['id']])}}"><i
                                            class="tio-download"></i></a>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

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
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('branch.order.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
