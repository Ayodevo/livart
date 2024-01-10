<ul class="list-unstyled">
    <li class="{{Request::is('admin/business-settings/app-setting')?'active':''}}"><a href="{{route('admin.business-settings.app_setting')}}">{{\App\CentralLogics\translate('app_settings')}}</a></li>
    <li class="{{Request::is('admin/business-settings/db*')?'active':''}}"><a href="{{route('admin.business-settings.db-index')}}">{{\App\CentralLogics\translate('clean_database')}}</a></li>
    <li class="{{Request::is('admin/business-settings/location-setup')?'active':''}}"><a href="{{route('admin.business-settings.location-setup')}}">{{\App\CentralLogics\translate('location_setup')}}</a></li>
</ul>
