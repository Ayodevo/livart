@extends('layouts.admin.app')

@section('title', \App\CentralLogics\translate('reCaptcha Setup'))

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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    @php($config=\App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                    <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.recaptcha_update',['recaptcha']):'javascript:'}}" method="post">
                        @csrf
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="text-uppercase mb-0">{{\App\CentralLogics\translate('reCaptcha')}}</h5>
                            <label class="switcher">
                                <input class="switcher_input" type="checkbox" name="status" {{isset($config) && $config['status'] == 1? 'checked' : ''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="flex-between">
                                <div class="btn-sm btn-dark p-2 cursor-pointer" data-toggle="modal" data-target="#recaptcha-modal">
                                    <i class="tio-info-outined"></i> {{\App\CentralLogics\translate('Credentials SetUp')}}
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="form-group">
                                    <label class="text-capitalize">{{\App\CentralLogics\translate('Site Key')}}</label>
                                    <input type="text" class="form-control" name="site_key"
                                           value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize">{{\App\CentralLogics\translate('Secret Key')}}</label>
                                    <input type="text" class="form-control" name="secret_key"
                                           value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn-primary">{{\App\CentralLogics\translate('save')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="recaptcha-modal" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog text-dark">
            <div class="modal-content"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="staticBackdropLabel">{{\App\CentralLogics\translate('reCaptcha credential Set up Instructions')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol>
                        <li>{{\App\CentralLogics\translate('Go to the Credentials page')}}
                            ({{\App\CentralLogics\translate('Click')}} <a
                                href="https://www.google.com/recaptcha/admin/create"
                                target="_blank">{{\App\CentralLogics\translate('here')}}</a>)
                        </li>
                        <li>{{\App\CentralLogics\translate('Add a ')}}
                            <b>{{\App\CentralLogics\translate('label')}}</b> {{\App\CentralLogics\translate('(Ex: Test Label)')}}
                        </li>
                        <li>
                            {{\App\CentralLogics\translate('Select reCAPTCHA v2 as ')}}
                            <b>{{\App\CentralLogics\translate('reCAPTCHA Type')}}</b>
                            ({{\App\CentralLogics\translate("Sub type: I'm not a robot Checkbox")}}
                            )
                        </li>
                        <li>
                            {{\App\CentralLogics\translate('Add')}}
                            <b>{{\App\CentralLogics\translate('domain')}}</b>
                            {{\App\CentralLogics\translate('(For ex: demo.6amtech.com)')}}
                        </li>
                        <li>
                            {{\App\CentralLogics\translate('Check in ')}}
                            <b>{{\App\CentralLogics\translate('Accept the reCAPTCHA Terms of Service')}}</b>
                        </li>
                        <li>
                            {{\App\CentralLogics\translate('Press')}}
                            <b>{{\App\CentralLogics\translate('Submit')}}</b>
                        </li>
                        <li>{{\App\CentralLogics\translate('Copy')}} <b>Site
                                Key</b> {{\App\CentralLogics\translate('and')}} <b>Secret
                                Key</b>, {{\App\CentralLogics\translate('paste in the input filed below and')}}
                            <b>Save</b>.
                        </li>
                    </ol>
                    <div class="d-flex justify-content-end mt-5">
                        <button type="button" class="btn btn-primary"
                        data-dismiss="modal">{{\App\CentralLogics\translate('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
