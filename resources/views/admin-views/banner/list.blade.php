@extends('layouts.admin.app')

@section('title', translate('Banner List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/banner.png')}}" alt="">
                {{\App\CentralLogics\translate('banner_list')}}
            </h2>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{\App\CentralLogics\translate('banner_table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $banners->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                            <form  action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search by Title')}}" aria-label="Search"
                                           value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{route('admin.banner.add-new')}}" class="btn btn-primary"><i
                                class="tio-add"></i> {{\App\CentralLogics\translate('add_new_banner')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{\App\CentralLogics\translate('SL')}}</th>
                            <th>{{\App\CentralLogics\translate('banner_image')}}</th>
                            <th>{{\App\CentralLogics\translate('title')}}</th>
                            <th>{{\App\CentralLogics\translate('type')}}</th>
                            <th>{{\App\CentralLogics\translate('status')}}</th>
                            <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $key=>$banner)
                        <tr>
                            <td>{{$banners->firstitem()+$key}}</td>
                            <td>
                                <div class="banner-img-wrap rounded border">
                                    <img class="img-fit" src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}"
                                    onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                                </div>
                            </td>
                            <td>{{$banner['title']}}</td>
                            <td>{{$banner['banner_type']}}</td>
                            <td>
                                @if($banner['status']==1)
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" checked id="{{$banner['id']}}"
                                               onclick="location.href='{{route('admin.banner.status',[$banner['id'],0])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @else
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input"  id="{{$banner['id']}}"
                                               onclick="location.href='{{route('admin.banner.status',[$banner['id'],1])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info square-btn"
                                        href="{{route('admin.banner.edit',[$banner['id']])}}"><i class="tio tio-edit"></i></a>
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="form_alert('banner-{{$banner['id']}}','{{\App\CentralLogics\translate('Want to delete this banner ?')}}')"><i class="tio tio-delete"></i></a>
                                </div>
                                <form action="{{route('admin.banner.delete',[$banner['id']])}}"
                                        method="post" id="banner-{{$banner['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $banners->links() !!}
                </div>
            </div>
            @if(count($banners)==0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')

@endpush
