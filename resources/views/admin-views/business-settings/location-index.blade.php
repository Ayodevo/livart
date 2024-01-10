@extends('layouts.admin.app')

@section('title', translate('Location Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
    @php($branch_count=\App\Model\Branch::count())
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/system-setting.png')}}" alt="">
                {{\App\CentralLogics\translate('system_setup')}}
            </h2>
        </div>

        <div class="inline-page-menu mb-4">
            @include('admin-views.business-settings.partial.system-setup-nav')

        </div>


        <div class="alert alert-soft-danger mb-4" role="alert">
            {{ translate("This location setup is for your Main branch. Carefully set your restaurant location and coverage area. If you want to ignore the coverage area then keep the input box empty.<br>
            You can ignore this when you have only the default branch and you don't want coverage area.") }}
        </div>


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.update-location')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @php($data=\App\Model\Branch::find(1))
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('latitude')}}</label>
                                <input type="text" value="{{$data['latitude']}}"
                                       name="latitude" class="form-control"
                                       placeholder="{{ translate('Ex : -94.22213') }}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('longitude')}}</label>
                                <input type="text" value="{{$data['longitude']}}"
                                       name="longitude" class="form-control"
                                       placeholder="{{ translate('Ex : 103.344322') }}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="">
                                    {{\App\CentralLogics\translate('coverage')}} ( {{\App\CentralLogics\translate('km')}} )
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius.') }}">
                                    </i>
                                </label>
                                <input type="number" value="{{$data['coverage']}}"
                                       name="coverage" class="form-control" placeholder="{{ translate('Ex : 3') }}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>
                    </div>

                    @if(env('APP_MODE')=='demo')
                        <div class="d-flex justify-content-end">
                            <button type="button"
                                onclick="call_demo()"
                                class="btn btn-primary">{{\App\CentralLogics\translate('update')}}
                            </button>
                        </div>
                    @else
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('update')}}</button>
                        </div>
                    @endif
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
