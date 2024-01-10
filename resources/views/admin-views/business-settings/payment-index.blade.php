@extends('layouts.admin.app')

@section('title', translate('Payment Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/third-party.png')}}" alt="">
                {{\App\CentralLogics\translate('3rd_Party')}}
            </h2>
        </div>

        <div class="inline-page-menu my-4">
            @include('admin-views.business-settings.partial.third-party-nav')
        </div>

        <div class="row gy-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CentralLogics\translate('payment')}} {{\App\CentralLogics\translate('method')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
                        <form action="{{route('admin.business-settings.payment-method-update',['cash_on_delivery'])}}"
                              method="post">
                            @csrf
                            @if(isset($config))
                                <div class="mb-2">
                                    <label class="control-label">{{\App\CentralLogics\translate('cash_on_delivery')}}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input id="active" type="radio" name="status" value="1" {{$config['status']==1?'checked':''}}>
                                    <label for="active" class="mb-0">{{\App\CentralLogics\translate('active')}}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <input id="inactive" type="radio" name="status" value="0" {{$config['status']==0?'checked':''}}>
                                    <label for="inactive" class="mb-0">{{\App\CentralLogics\translate('inactive')}}</label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('save')}}</button>
                                </div>
                                @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                    class="btn btn-primary">{{\App\CentralLogics\translate('configure')}}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CentralLogics\translate('payment')}} {{\App\CentralLogics\translate('method')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('digital_payment'))
                        <form action="{{route('admin.business-settings.payment-method-update',['digital_payment'])}}"
                              method="post">
                            @csrf
                            @if(isset($config))
                                <div class="mb-2">
                                    <label class="control-label">{{\App\CentralLogics\translate('digital')}} {{\App\CentralLogics\translate('payment')}}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input id="active2" type="radio" name="status" value="1" {{$config['status']==1?'checked':''}}>
                                    <label for="active2" class="mb-0">{{\App\CentralLogics\translate('active')}}</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <input id="inactive2" type="radio" name="status" value="0" {{$config['status']==0?'checked':''}}>
                                    <label for="inactive2" class="mb-0">{{\App\CentralLogics\translate('inactive')}}</label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('save')}}</button>
                                </div>
                                @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                    class="btn btn-primary">{{\App\CentralLogics\translate('configure')}}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="digital_payment_section">
            <div class="row g-2">
                @if($published_status == 1)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <h4 style="color: #8c1515; padding-top: 10px">
                                    <i class="tio-info-outined"></i>
                                    {{ translate('Your current payment settings are disabled, because you have enabled
                                    payment gateway addon, To visit your currently active payment gateway settings please follow
                                    the link.') }}
                                </h4>
                                <span>
                            <a href="{{!empty($payment_url) ? $payment_url : ''}}" class="btn btn-outline-primary"><i class="tio-settings mr-1"></i>{{translate('settings')}}</a>
                        </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row digital_payment_methods mt-3 g-3" id="payment-gatway-cards">
                @foreach($data_values as $payment)
                    <div class="col-md-6" style="margin-bottom: 30px">
                        <div class="card">
                            <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-config-update'):'javascript:'}}" method="POST"
                                  id="{{$payment->key_name}}-form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-header d-flex flex-wrap align-content-around">
                                    <h5>
                                        <span class="text-uppercase">{{str_replace('_',' ',$payment->key_name)}}</span>
                                    </h5>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <span class="mr-2 switch--custom-label-text text-primary on text-uppercase">on</span>
                                        <span class="mr-2 switch--custom-label-text off text-uppercase">off</span>
                                        <input type="checkbox" name="status" value="1"
                                               class="toggle-switch-input" {{$payment['is_active']==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>

                                @php($additional_data = $payment['additional_data'] != null ? json_decode($payment['additional_data']) : [])
                                <div class="card-body">
                                    <div class="payment--gateway-img">
                                        <img style="height: 80px"
                                             src="{{asset('storage/app/public/payment_modules/gateway_image')}}/{{$additional_data != null ? $additional_data->gateway_image : ''}}"
                                             onerror="this.src='{{asset('public/assets/admin/img/placeholder.png')}}'"
                                             alt="public">
                                    </div>

                                    <input name="gateway" value="{{$payment->key_name}}" class="d-none">

                                    @php($mode=$data_values->where('key_name',$payment->key_name)->first()->live_values['mode'])
                                    <div class="form-floating" style="margin-bottom: 10px">
                                        <select class="js-select form-control theme-input-style w-100" name="mode">
                                            <option value="live" {{$mode=='live'?'selected':''}}>Live</option>
                                            <option value="test" {{$mode=='test'?'selected':''}}>Test</option>
                                        </select>
                                    </div>

                                    @php($skip=['gateway','mode','status'])
                                    @foreach($data_values->where('key_name',$payment->key_name)->first()->live_values as $key=>$value)
                                        @if(!in_array($key,$skip))
                                            <div class="form-floating" style="margin-bottom: 10px">
                                                <label for="exampleFormControlInput1"
                                                       class="form-label">{{ucwords(str_replace('_',' ',$key))}}
                                                    *</label>
                                                <input type="text" class="form-control"
                                                       name="{{$key}}"
                                                       placeholder="{{ucwords(str_replace('_',' ',$key))}} *"
                                                       value="{{env('APP_MODE')=='demo'?'':$value}}">
                                            </div>
                                        @endif
                                    @endforeach

                                    <div class="form-floating" style="margin-bottom: 10px">
                                        <label for="exampleFormControlInput1"
                                               class="form-label">{{translate('payment_gateway_title')}}</label>
                                        <input type="text" class="form-control"
                                               name="gateway_title"
                                               placeholder="{{translate('payment_gateway_title')}}"
                                               value="{{$additional_data != null ? $additional_data->gateway_title : ''}}">
                                    </div>

                                    <div class="form-floating" style="margin-bottom: 10px">
                                        <label for="exampleFormControlInput1"
                                               class="form-label">{{translate('choose logo')}}</label>
                                        <input type="file" class="form-control" name="gateway_image" accept=".jpg, .png, .jpeg|image/*">
                                    </div>

                                    <div class="text-right" style="margin-top: 20px">
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                                class="btn btn-primary px-5">{{translate('save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
@endsection

@push('script_2')

    <script>

        $(document).on('change', 'input[name="gateway_image"]', function () {
            var $input = $(this);
            var $form = $input.closest('form');
            var gatewayName = $form.attr('id');

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                var $imagePreview = $form.find('.payment--gateway-img img'); // Find the img element within the form

                reader.onload = function (e) {
                    $imagePreview.attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        @if($published_status == 1)
            $('#payment-gatway-cards').find('input').each(function(){
                $(this).attr('disabled', true);
            });
            $('#payment-gatway-cards').find('select').each(function(){
                $(this).attr('disabled', true);
            });
            $('#payment-gatway-cards').find('.switcher_input').each(function(){
                $(this).removeAttr('checked', true);
            });
            $('#payment-gatway-cards').find('button').each(function(){
                $(this).attr('disabled', true);
            });
        @endif

    </script>
@endpush