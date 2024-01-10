@extends('layouts.admin.app')

@section('title', translate('Cookies Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/business-setup.png')}}" alt="">
                {{\App\CentralLogics\translate('business_Setup')}}
            </h2>
        </div>

        <div class="inline-page-menu mb-4">
            @include('admin-views.business-settings.partial.business-setup-nav')
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.update-cookies')}}" method="post">
                    @csrf
                    @php($cookies=\App\CentralLogics\Helpers::get_business_settings('cookies'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap justify-content-between">
                                <span class="text-dark">{{translate('cookies_text')}}</span>
                                <label class="switch-custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                    <input type="checkbox" name="status" value="1" class="toggle-switch-input" {{$cookies?($cookies['status']==1?'checked':''):''}}>
                                    <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                </label>
                            </div>
                            <div class="form-group pt-3">
                                <textarea name="text" class="form-control" rows="6" placeholder="{{ translate('Cookies text') }}" required> {{ $cookies['text'] }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{\App\CentralLogics\translate('update')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
