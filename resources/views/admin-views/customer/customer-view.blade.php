@extends('layouts.admin.app')

@section('title', translate('Customer Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="mb-3">
                <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('public/assets/admin/img/icons/customer.png')}}" alt="">
                    {{\App\CentralLogics\translate('Customer Details')}}
                </h2>
            </div>

            <div class="border-top"></div>

            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center py-3">
                <div class="">
                    <h3 class="page-header-title">{{\App\CentralLogics\translate('customer_ID')}} #{{$customer['id']}}</h3>
                    <div class="fs-12">
                        <i class="tio-date-range"></i>
                        {{\App\CentralLogics\translate('joined_at')}} : {{date('d M Y H:i:s',strtotime($customer['created_at']))}}
                    </div>
                </div>

                <a href="{{route('admin.dashboard')}}" class="btn btn-primary">
                    <i class="tio-home-outlined"></i> {{\App\CentralLogics\translate('dashboard')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-8">
                <div class="card mb-3 mb-lg-0">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-sm-4">
                                <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                                    {{\App\CentralLogics\translate('customer_table')}}
                                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $orders->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8">
                                <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                                    <form action="{{url()->current()}}" method="GET">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="{{translate('Search by Order ID')}}" aria-label="Search"
                                                value="{{$search}}" required autocomplete="off">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CentralLogics\translate('SL')}}</th>
                                    <th>{{\App\CentralLogics\translate('order_ID')}}</th>
                                    <th>{{\App\CentralLogics\translate('total')}}</th>
                                    <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $key=>$order)
                                <tr>
                                    <td>{{$orders->firstitem()+$key}}</td>
                                    <td>
                                        <a class="text-dark" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                    </td>
                                    <td>{{ Helpers::set_symbol($order['order_amount']) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info square-btn"
                                                href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                <i class="tio-visible"></i>
                                            </a>
                                            <a class="btn btn-outline-primary square-btn" target="_blank"
                                                href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                                <i class="tio-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0 d-flex align-items-center gap-2"><i class="tio tio-user"></i> {{\App\CentralLogics\translate('customer_information')}}</h4>
                    </div>

                    <!-- Body -->
                    @if($customer)
                        <div class="card-body">
                            <div class="media gap-3 flex-wrap align-items-center">
                                <div class="avatar-lg avatar-circle mr-3">
                                    <img
                                        class="img-fit rounded-circle"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/profile/'.$customer->image)}}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body text-dark">
                                    <div class="">{{$customer['f_name'].' '.$customer['l_name']}}</div>
                                    <div class=""><strong>{{$customer->orders->count()}}</strong> {{\App\CentralLogics\translate('orders')}}</div>
                                    <a class="text-dark d-flex" href="tel:{{$customer['phone']}}"><strong>{{$customer['phone']}}</strong></a>
                                    <a class="text-dark d-flex" href="mailto:{{$customer['email']}}">{{$customer['email']}}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0 d-flex align-items-center gap-2"><i class="tio tio-user"></i> {{\App\CentralLogics\translate('addresses')}}</h4>
                    </div>

                    <!-- Body -->
                    @if($customer)
                        <div class="card-body">
                            @foreach($customer->addresses as $key=>$address)
                                <ul class="list-unstyled list-unstyled-py-2 text-dark">
                                    <li>
                                        <i class="tio-tab mr-2"></i>
                                        {{$address['address_type']}}
                                    </li>
                                    <li>
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        {{$address['contact_person_number']}}
                                    </li>
                                    <li>
                                        <a class="text-dark" target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}">
                                            <i class="tio-map mr-2"></i>
                                            {{$address['address']}}
                                        </a>
                                    </li>
                                </ul>
                                @if($key+1 < count($customer->addresses))
                                    <hr>
                                @endif
                            @endforeach

                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
