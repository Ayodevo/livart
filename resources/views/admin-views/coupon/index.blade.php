@extends('layouts.admin.app')

@section('title', \App\CentralLogics\translate('Add new coupon'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/coupon.png')}}" alt="">
                {{\App\CentralLogics\translate('add_new_coupon')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.coupon.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('title')}}</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="{{ \App\CentralLogics\translate('New coupon') }}" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('coupon')}} {{\App\CentralLogics\translate('type')}}</label>
                                <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="default">{{\App\CentralLogics\translate('default')}}</option>
                                    <option value="first_order">{{\App\CentralLogics\translate('first order')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6" id="limit-for-user">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('limit')}} {{\App\CentralLogics\translate('for')}} {{\App\CentralLogics\translate('same')}} {{\App\CentralLogics\translate('user')}}</label>
                                <input type="number" name="limit" id="user-limit" value="{{ old('limit') }}" class="form-control" placeholder="{{ \App\CentralLogics\translate('EX: 10') }}" required min="1">
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label class="input-label">{{\App\CentralLogics\translate('code')}}</label>
                                    <a href="javascript:void(0)" class="float-right c1 fz-12" onclick="generateCode()">{{translate('generate_code')}}</a>
                                </div>
                                <input type="text" name="code" class="form-control" maxlength="15" id="code" value="{{ old('code') }}"
                                       placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('start')}} {{\App\CentralLogics\translate('date')}}</label>
                                <input type="text" name="start_date" id="start_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select date') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('expire')}} {{\App\CentralLogics\translate('date')}}</label>
                                <input type="text" name="expire_date" id="expire_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select date') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('min')}} {{\App\CentralLogics\translate('purchase')}}</label>
                                <input type="number" step="0.01" name="min_purchase" value="{{ old('min_purchase')}}" min="0" max="10000000" class="form-control"
                                       placeholder="{{ \App\CentralLogics\translate('100') }}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">
                                    {{\App\CentralLogics\translate('discount')}}
                                    {{\App\CentralLogics\translate('type')}}
                                </label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="percent">{{\App\CentralLogics\translate('percent')}}</option>
                                    <option value="amount">{{\App\CentralLogics\translate('amount')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('discount')}}</label>
                                <input type="number" step="0.01" min="1" max="10000000" name="discount" value="{{old('discount') }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6" id="max_discount_div">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('max')}} {{\App\CentralLogics\translate('discount')}}</label>
                                <input type="number" step="0.01" min="0" value="{{ old('max_discount') }}" max="10000000" name="max_discount" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{\App\CentralLogics\translate('Reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{\App\CentralLogics\translate('coupon_table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $coupons->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                            <form action="#" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search by Title')}}" aria-label="Search"
                                        value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{\App\CentralLogics\translate('SL')}}</th>
                        <th>{{\App\CentralLogics\translate('title')}}</th>
                        <th>{{\App\CentralLogics\translate('code')}}</th>
                        <th>{{\App\CentralLogics\translate('min')}} {{\App\CentralLogics\translate('purchase')}}</th>
                        <th>{{\App\CentralLogics\translate('max')}} {{\App\CentralLogics\translate('discount')}}</th>
                        <th>{{\App\CentralLogics\translate('discount')}}</th>
                        <th>{{\App\CentralLogics\translate('discount')}} {{\App\CentralLogics\translate('type')}}</th>
                        <th>{{\App\CentralLogics\translate('start')}} {{\App\CentralLogics\translate('date')}}</th>
                        <th>{{\App\CentralLogics\translate('expire')}} {{\App\CentralLogics\translate('date')}}</th>
                        <th>{{\App\CentralLogics\translate('status')}}</th>
                        <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($coupons as $key=>$coupon)
                        <tr>
                            <td>{{$coupons->firstItem()+$key}}</td>
                            <td>{{$coupon['title']}}</td>
                            <td>{{$coupon['code']}}</td>
                            <td>{{ Helpers::set_symbol($coupon['min_purchase']) }}</td>
                            <td>{{ $coupon['discount_type'] == 'percent' ? Helpers::set_symbol($coupon['max_discount']) : '-' }}</td>
                            <td>{{$coupon['discount']}}</td>
                            <td>{{translate($coupon['discount_type'])}}</td>
                            <td>{{date('d-m-Y', strtotime($coupon['start_date']))}}</td>
                            <td>{{date('d-m-Y', strtotime($coupon['expire_date']))}}</td>
                            <td>
                                @if($coupon['status']==1)
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" checked id="{{$coupon['id']}}"
                                               onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],0])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @else
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input"  id="{{$coupon['id']}}"
                                               onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],1])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-warning square-btn" id="coupon_details"
                                       href="#" data-toggle="modal" onclick="get_details(this)" data-id="{{ $coupon['id'] }}" data-toggle="modal" data-target="#exampleModalCenter">
                                        <i class="tio-invisible"></i>
                                    </a>

                                    <a class="btn btn-outline-info square-btn"
                                        href="{{route('admin.coupon.update',[$coupon['id']])}}"><i class="tio tio-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="form_alert('coupon-{{$coupon['id']}}','{{\App\CentralLogics\translate('Want to delete this attribute ?')}}')"><i class="tio tio-delete"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                        method="post" id="coupon-{{$coupon['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $coupons->links() !!}
                </div>
            </div>
            @if(count($coupons)==0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="quick-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
            <div class="modal-content" id="quick-view-modal">
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $("#discount_type").change(function(){
            if(this.value === 'amount') {
                $("#max_discount_div").hide();
            }
            else if(this.value === 'percent') {
                $("#max_discount_div").show();
            }
        });
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

        function coupon_type_change(order_type) {
            if(order_type=='first_order'){
                $('#limit-for-user').hide();
                $('#user-limit').removeAttr('required');
            }else{
                $('#user-limit').prop('required',true);
                $('#limit-for-user').show();
            }
        }

        function  generateCode(){
            let code = Math.random().toString(36).substring(2,12);
            $('#code').val(code)
        }

        $('#start_date,#expire_date').change(function () {
            let fr = $('#start_date').val();
            let to = $('#expire_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#start_date').val('');
                    $('#expire_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        });

        function get_details(t){
            let id = $(t).data('id')

            $.ajax({
                type: 'GET',
                url: '{{route('admin.coupon.details')}}',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                }
            });
        }
    </script>
@endpush
