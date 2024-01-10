@extends('layouts.admin.app')

@section('title', translate('Mail Settings'))

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

        <div class="col-xl-8 p-0">
            <div class="card mb-3">
                <div class="card-body">

                    <div class="position-relative">
                        <button class="btn btn-secondary" type="button" data-toggle="collapse"
                                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <i class="tio-email-outlined"></i>
                            {{translate('test_your_email_integration')}}
                        </button>

                    </div>

                    <div class="collapse" id="collapseExample">
                        <form class="pt-3" action="javascript:">
                            <div class="row g-2">
                                <div class="col-sm-8">
                                    <div class="form-group mb-0">
                                        <label for="inputPassword2" class="sr-only">{{translate('mail')}}</label>
                                        <input type="email" id="test-email" class="form-control" placeholder="Ex : jhon@email.com">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" onclick="send_mail()" class="btn btn-primary h-100 btn-block">
                                        <i class="tio-telegram"></i>
                                        {{translate('send_mail')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            @php($config=\App\Model\BusinessSetting::where(['key'=>'mail_config'])->first())
            @php($data=json_decode($config['value'],true))
            @php($status=$data['status']== 1 ? 0 : 1)
            <div class="card-body">
                <div class="d-flex flex-wrap mb-3">
                    <label class="control-label h3 text-capitalize mr-3">{{translate('mail configuration status')}}</label>
                    <div class="custom--switch">
                        <input type="checkbox" name="status" value="" id="switch6" switch="primary"
                               onclick="mail_status_change('{{route('admin.business-settings.mail-config.status',[$status])}}')"
                               class="toggle-switch-input" id="stocksCheckbox{{ 1 }}" {{ $data['status'] ==  1 ? 'checked' : '' }}>
                        <label for="switch6" data-on-label="on" data-off-label="off"></label>
                    </div>
                </div>
                <form action="{{route('admin.business-settings.mail-config')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($config))
                        <div class="row">
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('mailer')}} {{\App\CentralLogics\translate('name')}}</label>
                                    <input type="text" placeholder="{{ translate('ex : Alex') }}" class="form-control" name="name"
                                        value="{{env('APP_MODE')=='demo'?'':$data['name']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('host')}}</label>
                                    <input type="text" class="form-control" name="host"
                                        value="{{env('APP_MODE')=='demo'?'':$data['host']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('driver')}}</label>
                                    <input type="text" class="form-control" name="driver"
                                        value="{{env('APP_MODE')=='demo'?'':$data['driver']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('port')}}</label>
                                    <input type="text" class="form-control" name="port"
                                        value="{{env('APP_MODE')=='demo'?'':$data['port']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('username')}}</label>
                                    <input type="text" placeholder="{{ translate('ex : ex@yahoo.com') }}" class="form-control" name="username"
                                        value="{{env('APP_MODE')=='demo'?'':$data['username']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('email')}} {{\App\CentralLogics\translate('id')}}</label>
                                    <input type="text" placeholder="{{ translate('ex : ex@yahoo.com') }}" class="form-control" name="email"
                                        value="{{env('APP_MODE')=='demo'?'':$data['email_id']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('encryption')}}</label>
                                    <input type="text" placeholder="{{ translate('ex : tls') }}" class="form-control" name="encryption"
                                        value="{{env('APP_MODE')=='demo'?'':$data['encryption']}}" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label>{{\App\CentralLogics\translate('password')}}</label>
                                    <input type="text" class="form-control" name="password"
                                        value="{{env('APP_MODE')=='demo'?'':$data['password']}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            @if(env('APP_MODE')=='demo')
                                <button type="button"
                                        onclick="call_demo()"
                                        class="btn btn-primary">{{ translate('Save Changes') }}
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary mb-2">{{\App\CentralLogics\translate('save')}}</button>
                            @endif
                        </div>
                    @else
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mb-2">{{\App\CentralLogics\translate('configure')}}</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function mail_status_change(route) {

            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function ValidateEmail(inputText) {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (inputText.match(mailformat)) {
                return true;
            } else {
                return false;
            }
        }

        function send_mail() {
            if (ValidateEmail($('#test-email').val())) {
                Swal.fire({
                    title: '{{translate('Are you sure?')}}?',
                    text: "{{translate('a_test_mail_will_be_sent_to_your_email')}}!",
                    showCancelButton: true,
                    confirmButtonColor: '#673ab7',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{translate('Yes')}}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{route('admin.business-settings.mail-send')}}",
                            method: 'POST',
                            data: {
                                "email": $('#test-email').val()
                            },
                            beforeSend: function () {
                                $('#loading').show();
                            },
                            success: function (data) {
                                if (data.success === 2) {
                                    toastr.error('{{translate('email_configuration_error')}} !!');
                                } else if (data.success === 1) {
                                    toastr.success('{{translate('email_configured_perfectly!')}}!');
                                } else {
                                    toastr.info('{{translate('email_status_is_not_active')}}!');
                                }
                            },
                            complete: function () {
                                $('#loading').hide();

                            }
                        });
                    }
                })
            } else {
                toastr.error('{{translate('invalid_email_address')}} !!');
            }
        }

    </script>
@endpush
