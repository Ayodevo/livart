@extends('layouts.admin.app')

@section('title', translate('Sale Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/order_report.png')}}" alt="">
                {{\App\CentralLogics\translate('sale_Report')}}
            </h2>
        </div>

        <div class="card card-body mb-3">
            <div class="media gap-3 flex-column flex-sm-row align-items-sm-center">
                <!-- Avatar -->
                <div class="avatar avatar-xl avatar-4by3">
                    <img class="avatar-img" src="{{asset('public/assets/admin')}}/svg/illustrations/credit-card.svg"
                         alt="Image Description">
                </div>
                <!-- End Avatar -->

                <div class="media-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div class="text-capitalize">
                            <h2 class="page-header-title">
                                {{\App\CentralLogics\translate('sale')}}
                                {{\App\CentralLogics\translate('report')}}
                                {{\App\CentralLogics\translate('overview')}}
                            </h2>

                            <div class="">
                                <span>{{\App\CentralLogics\translate('admin')}}:</span>
                                <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                            </div>
                        </div>

                        <div class="d-flex">
                            <a class="btn btn-icon btn-primary rounded-circle" href="{{route('admin.dashboard')}}">
                                <i class="tio-home-outlined"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-xl-3 mb-3">
                            <select class="custom-select custom-select" name="branch_id" id="branch_id">
                                <option value="all">{{\App\CentralLogics\translate('all')}} {{\App\CentralLogics\translate('branch')}}</option>
                                @foreach($branches as $branch)
                                    <option value="{{$branch['id']}}" {{ $branch_id==$branch['id']?'selected':''}}>{{$branch['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-xl-3 mb-3">
                            <input type="date" name="start_date" id="from_date" value="{{ $start_date }}" class="form-control">
                        </div>

                        <div class="col-sm-6 col-xl-3 mb-3">
                            <input type="date" name="end_date" id="to_date" value="{{ $end_date }}" class="form-control">
                        </div>

                        <div class="col-sm-6 col-xl-3 mb-3 __btn-row">
                            <a href="{{ route('admin.report.sale-report') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                            <button type="submit" class="btn btn-primary btn-block">{{\App\CentralLogics\translate('show')}}</button>
                        </div>

                        <div class="col-md-6 pt-4">
                            <strong>{{\App\CentralLogics\translate('total')}} {{\App\CentralLogics\translate('orders')}} :
                                <span id=""> {{ count($orders) }}</span>
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
                                   href="{{route('admin.report.export-sale-report', ['branch_id'=>Request::get('branch_id'), 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date')])}}">
                                    <i class="tio-download-to mr-1"></i> {{\App\CentralLogics\translate('export')}}
                                </a>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
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
                                    <td class="">{{$key+1}}</td>
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
    </div>
@endsection

@push('script_2')

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
