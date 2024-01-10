<ul class="list-unstyled">
    <li class="{{Request::is('admin/business-settings/ecom-setup')?'active':''}}"><a href="{{route('admin.business-settings.ecom-setup')}}">{{\App\CentralLogics\translate('business_setup')}}</a></li>
    <li class="{{Request::is('admin/business-settings/delivery-fee-setup')?'active':''}}"><a href="{{route('admin.business-settings.delivery-fee-setup')}}">{{\App\CentralLogics\translate('delivery_fee_setup')}}</a></li>
    <li class="{{Request::is('admin/business-settings/otp-setup')?'active':''}}"><a href="{{route('admin.business-settings.otp-setup')}}">{{\App\CentralLogics\translate('OTP_and_login_setup')}}</a></li>
    <li class="{{Request::is('admin/business-settings/cookies-setup')?'active':''}}"><a href="{{route('admin.business-settings.cookies-setup')}}">{{\App\CentralLogics\translate('cookies_setup')}}</a></li>
</ul>
