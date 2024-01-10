@extends('layouts.admin.app')

@section('title', translate('Map API Settings'))

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

        <div class="card">
            <div class="card-body">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.map_api_settings'):'javascript:'}}" method="post"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @php($key=\App\Model\BusinessSetting::where('key','map_api_key')->first()?->value)
                        <div class="form-group col-md-6">
                            <label class="input-label">{{\App\CentralLogics\translate('map_api_client_key')}}</label>
                            <textarea name="map_api_key" class="form-control">{{env('APP_MODE')!='demo'?$key:''}}</textarea>
                        </div>
                        @php($server_key=\App\Model\BusinessSetting::where('key','map_api_server_key')->first()?->value)
                        <div class="form-group col-md-6">
                            <label class="input-label">{{\App\CentralLogics\translate('map_api_server_key')}}</label>
                            <textarea name="map_api_server_key" class="form-control">{{env('APP_MODE')!='demo'?$server_key:''}}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{\App\CentralLogics\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
