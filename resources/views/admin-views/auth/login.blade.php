<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Admin') }} | {{ translate('Login') }}</title>

    <!-- Favicon -->
    @php($icon = \App\Model\BusinessSetting::where(['key' => 'fav_icon'])->first()->value)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/app/public/ecommerce/' . $icon ?? '') }}">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
{{--<main id="content" role="main" class="main d-none">--}}

{{--    <!-- Content -->--}}
{{--    <div class="container py-5 py-sm-7">--}}
{{--        <label class="badge badge-soft-success float-right" style="z-index: 9;position: absolute;right: 0.5rem;top: 0.5rem;">{{ translate('Software version') }} : {{ env('SOFTWARE_VERSION') }}</label>--}}
{{--        <a class="d-flex justify-content-center mb-5" href="javascript:">--}}
{{--            <img class="z-index-2"--}}
{{--                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"--}}
{{--                 src="{{asset('storage/app/public/ecommerce')}}/{{\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value??''}}"--}}
{{--                 alt="Image Description" style="width: 300px;">--}}
{{--        </a>--}}

{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-7 col-lg-5">--}}
{{--                <!-- Card -->--}}
{{--                <div class="card card-lg mb-5">--}}
{{--                    <div class="card-body">--}}
{{--                        <!-- Form -->--}}
{{--                        <form id="form-id" action="{{route('admin.auth.login')}}" method="post">--}}
{{--                            @csrf--}}

{{--                            <div class="text-center">--}}
{{--                                <div class="mb-5">--}}
{{--                                    <h1 class="display-4"> {{\App\CentralLogics\translate('sign_in')}}</h1>--}}
{{--                                    <p>{{\App\CentralLogics\translate('want to login your branches')}}--}}
{{--                                        ?--}}
{{--                                        <a href="{{route('branch.auth.login')}}">--}}
{{--                                            {{\App\CentralLogics\translate('branch')}} {{\App\CentralLogics\translate('login')}}--}}
{{--                                        </a>--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Form Group -->--}}
{{--                            <div class="js-form-message form-group">--}}
{{--                                <label class="input-label text-capitalize"--}}
{{--                                       for="signinSrEmail">{{\App\CentralLogics\translate('your')}} {{\App\CentralLogics\translate('email')}}</label>--}}

{{--                                <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"--}}
{{--                                       tabindex="1" placeholder="{{ translate('email@address.com') }}" aria-label="email@address.com"--}}
{{--                                       required data-msg="Please enter a valid email address.">--}}
{{--                            </div>--}}
{{--                            <!-- End Form Group -->--}}

{{--                            <!-- Form Group -->--}}
{{--                            <div class="js-form-message form-group">--}}
{{--                                <label class="input-label" for="signupSrPassword" tabindex="0">--}}
{{--                                    <span class="d-flex justify-content-between align-items-center">--}}
{{--                                      {{\App\CentralLogics\translate('password')}}--}}
{{--                                    </span>--}}
{{--                                </label>--}}

{{--                                <div class="input-group input-group-merge">--}}
{{--                                    <input type="password" class="js-toggle-password form-control form-control-lg"--}}
{{--                                           name="password" id="signupSrPassword" placeholder="8+ characters required"--}}
{{--                                           aria-label="{{ translate('8+ characters required') }}" required--}}
{{--                                           data-msg="{{ translate('Your password is invalid. Please try again.') }}"--}}
{{--                                           data-hs-toggle-password-options='{--}}
{{--                                                     "target": "#changePassTarget",--}}
{{--                                            "defaultClass": "tio-hidden-outlined",--}}
{{--                                            "showClass": "tio-visible-outlined",--}}
{{--                                            "classChangeTarget": "#changePassIcon"--}}
{{--                                            }'>--}}
{{--                                    <div id="changePassTarget" class="input-group-append">--}}
{{--                                        <a class="input-group-text" href="javascript:">--}}
{{--                                            <i id="changePassIcon" class="tio-visible-outlined"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- End Form Group -->--}}

{{--                            <!-- Checkbox -->--}}
{{--                            <div class="form-group">--}}
{{--                                <div class="custom-control custom-checkbox">--}}
{{--                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"--}}
{{--                                           name="remember">--}}
{{--                                    <label class="custom-control-label text-muted" for="termsCheckbox">--}}
{{--                                        {{\App\CentralLogics\translate('remember')}} {{\App\CentralLogics\translate('me')}}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- End Checkbox -->--}}

{{--                            --}}{{-- recaptcha --}}
{{--                            @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))--}}
{{--                            @if(isset($recaptcha) && $recaptcha['status'] == 1)--}}
{{--                                <div id="recaptcha_element" style="width: 100%;" data-type="image"></div>--}}
{{--                                <br/>--}}
{{--                            @else--}}
{{--                                <div class="row p-2">--}}
{{--                                    <div class="col-6 pr-0">--}}
{{--                                        <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""--}}
{{--                                               placeholder="{{\App\CentralLogics\translate('Enter captcha value')}}" style="border: none" autocomplete="off">--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 input-icons" style="background-color: #FFFFFF; border-radius: 5px;">--}}
{{--                                        <a onclick="javascript:re_captcha();">--}}
{{--                                            <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field" id="default_recaptcha_id" style="display: inline;width: 90%; height: 75%">--}}
{{--                                            <i class="tio-refresh icon"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}

{{--                            <button type="submit" class="btn btn-lg btn-block btn-primary">{{\App\CentralLogics\translate('sign_in')}}</button>--}}
{{--                        </form>--}}
{{--                        <!-- End Form -->--}}
{{--                    </div>--}}
{{--                    @if(env('APP_MODE')=='demo')--}}
{{--                        <div class="card-footer">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-10">--}}
{{--                                    <span>{{ translate('Email : admin@admin.com') }}</span><br>--}}
{{--                                    <span>{{ translate('Password : 12345678') }}</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-2">--}}
{{--                                    <button class="btn btn-primary" onclick="copy_cred()"><i class="tio-copy"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--                <!-- End Card -->--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- End Content -->--}}
{{--</main>--}}

<main id="content" role="main" class="main">
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <div class="d-none d-md-flex justify-content-center flex-grow-1 bg-light login-bg-box" data-bg-img="{{asset('public/assets/admin/img/login_bg.png')}}">
            <div class="login-left-content p-3">
                <a class="d-flex mb-4" href="javascript:">
                    <img class="z-index-2"
                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                        src="{{asset('storage/app/public/ecommerce')}}/{{\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value??''}}"
                        alt="Image Description" style="height: 60px;">
                </a>

                <h3 class="mb-0">{{ translate('Your') }} <br /> {{ translate('All Service') }}</h3>
                <h2 class="text-primary font-weight-bold">{{ translate('in one field') }}....</h2>
            </div>
        </div>
        <div class="flex-grow-1 bg-white d-flex justify-content-center">
            <div class="card-content-wrap pb-5 pb-md-0">
                <div class="card-body">
                    <div class="software-version d-flex justify-content-end">
                        <label class="badge badge-soft-success __login-badge text-primary">{{ translate('Software version') }} : {{ env('SOFTWARE_VERSION') }}</label>
                    </div>

                    <!-- Form -->
                    <form id="form-id" action="{{route('admin.auth.login')}}" method="post">
                        @csrf

                        <div class="">
                            <div class="mb-5">
                                <h3 class="display-4"> {{\App\CentralLogics\translate('sign_in')}}</h3>
                                <p>{{\App\CentralLogics\translate('want to login your branches')}}
                                    ?
                                    <a href="{{route('branch.auth.login')}}">
                                        {{\App\CentralLogics\translate('branch')}} {{\App\CentralLogics\translate('login')}}
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- Form Group -->
                        <div class="js-form-message form-group">
                            <label class="input-label text-capitalize"
                                    for="signinSrEmail">{{\App\CentralLogics\translate('your')}} {{\App\CentralLogics\translate('email')}}</label>

                            <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                    tabindex="1" placeholder="{{ translate('email@address.com') }}" aria-label="email@address.com"
                                    required data-msg="Please enter a valid email address.">
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="js-form-message form-group">
                            <label class="input-label" for="signupSrPassword" tabindex="0">
                                <span class="d-flex justify-content-between align-items-center">
                                    {{\App\CentralLogics\translate('password')}}
                                </span>
                            </label>

                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password" id="signupSrPassword" placeholder="8+ characters required"
                                        aria-label="{{ translate('8+ characters required') }}" required
                                        data-msg="{{ translate('Your password is invalid. Please try again.') }}"
                                        data-hs-toggle-password-options='{
                                                    "target": "#changePassTarget",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#changePassIcon"
                                        }'>
                                <div id="changePassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Form Group -->

                        <!-- Checkbox -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                        name="remember">
                                <label class="custom-control-label text-muted" for="termsCheckbox">
                                    {{\App\CentralLogics\translate('remember')}} {{\App\CentralLogics\translate('me')}}
                                </label>
                            </div>
                        </div>
                        <!-- End Checkbox -->

                        {{-- recaptcha --}}
                        <div class="mb-4">
                            @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div id="recaptcha_element" style="width: 100%;" data-type="image"></div>
                                <br/>
                            @else
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""
                                                placeholder="{{\App\CentralLogics\translate('Enter captcha value')}}" autocomplete="off">
                                    </div>
                                    <div class="col-6">
                                        <a onclick="javascript:re_captcha();">
                                            <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field rounded" id="default_recaptcha_id" style="height: 54px">
                                            <i class="tio-refresh icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-block btn-primary">{{\App\CentralLogics\translate('sign_in')}}</button>
                    </form>
                    <!-- End Form -->

                    @if(env('APP_MODE')=='demo')
                    <div class="login-footer d-flex justify-content-between mt-4 border-top pt-3">
                        <div class="font-weight-medium">
                            <div>{{ translate('Email : admin@admin.com') }}</div>
                            <div>{{ translate('Password : 12345678') }}</div>
                        </div>
                        <button type="button" class="btn btn-primary login-copy" onclick="copy_cred()">
                            <i class="tio-copy"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->

<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });

        // BACKGROUND IMAGE
        // =======================================================
        var $bgImg = $("[data-bg-img]");
        $bgImg
            .css("background-image", function () {
                return 'url("' + $(this).data("bg-img") + '")';
            })
            .removeAttr("data-bg-img")
            .addClass("bg-img");
        });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CentralLogics\translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/admin/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('admin@admin.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif
<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
