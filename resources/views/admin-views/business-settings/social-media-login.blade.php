@extends('layouts.admin.app')

@section('title', translate('Social Media Login'))

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

        <div class="row g-3">
            <div class="col-md-6">
                <?php
                $google=\App\Model\BusinessSetting::where('key','google_social_login')->first()?->value;
                $status = $google == 1 ? 0 : 1;
                ?>
                <div class="card __social-media-login __shadow">
                    <div class="card-body">
                        <div class="__social-media-login-top">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('/public/assets/admin/img/google.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt">{{translate('Google Login')}}</div>
                            <div class="custom--switch switch--right">
                                <input type="checkbox" id="google_social_login" name="google" switch="primary" class="toggle-switch-input" {{ $google == 1 ? 'checked' : '' }}>
                                <label for="google_social_login" data-on-label="on" data-off-label="off"
                                       onclick="change_social_login_status('{{route('admin.business-settings.social_login_status',['google', $status])}}')"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php
                $facebook =\App\Model\BusinessSetting::where('key','facebook_social_login')->first()?->value;
                $status = $facebook == 1 ? 0 : 1;
                ?>
                <div class="card __social-media-login __shadow">
                    <div class="card-body">
                        <div class="__social-media-login-top">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('/public/assets/admin/img/facebook.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt">{{translate('Facebook Login')}}</div>
                            <div class="custom--switch switch--right">
                                <input type="checkbox" id="facebook" name="facebook_social_login" switch="primary" class="toggle-switch-input" {{ $facebook == 1 ? 'checked' : '' }}>
                                <label for="facebook" data-on-label="on" data-off-label="off"
                                       onclick="change_social_login_status('{{route('admin.business-settings.social_login_status',['facebook', $status])}}')"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script_2')
    <script>
        function change_social_login_status(route) {
            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    setTimeout(function () {
                        location.reload(true);
                    }, 1000);
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

    </script>
@endpush
