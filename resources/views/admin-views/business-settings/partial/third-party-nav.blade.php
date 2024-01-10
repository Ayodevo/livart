<ul class="list-unstyled">
    <li class="{{Request::is('admin/business-settings/sms-module')?'active':''}}"><a href="{{route('admin.business-settings.sms-module')}}">{{\App\CentralLogics\translate('SMS_Module')}}</a></li>
    <li class="{{Request::is('admin/business-settings/mail-config')?'active':''}}"><a href="{{route('admin.business-settings.mail-config')}}">{{\App\CentralLogics\translate('Mail_Config')}}</a></li>
    <li class="{{Request::is('admin/business-settings/payment-method')?'active':''}}"><a href="{{route('admin.business-settings.payment-method')}}">{{\App\CentralLogics\translate('Payment_Methods')}}</a></li>
    <li class="{{Request::is('admin/business-settings/recaptcha*')?'active':''}}"><a href="{{route('admin.business-settings.recaptcha_index')}}">{{\App\CentralLogics\translate('Recaptcha')}}</a></li>
    <li class="{{Request::is('admin/business-settings/map-api-settings')?'active':''}}"><a href="{{route('admin.business-settings.map_api_settings')}}">{{\App\CentralLogics\translate('Google_Map_APIs')}}</a></li>
    <li class="{{Request::is('admin/business-settings/fcm-index')?'active':''}}"><a href="{{route('admin.business-settings.fcm-index')}}">{{\App\CentralLogics\translate('Push_Notification')}}</a></li>
    <li class="{{Request::is('admin/business-settings/firebase-message-config')?'active':''}}"><a href="{{route('admin.business-settings.firebase_message_config_index')}}">{{\App\CentralLogics\translate('Firebase_Message_Config')}}</a></li>
    <li class="{{Request::is('admin/business-settings/social-media-login')?'active':''}}"><a href="{{route('admin.business-settings.social-media-login')}}">{{\App\CentralLogics\translate('social_media_login')}}</a></li>
    <li class="{{Request::is('admin/business-settings/social-media-chat')?'active':''}}"><a href="{{route('admin.business-settings.social-media-chat')}}">{{\App\CentralLogics\translate('social_media_chat')}}</a></li>
</ul>
