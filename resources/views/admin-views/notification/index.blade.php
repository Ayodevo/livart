@extends('layouts.admin.app')

@section('title', translate('Add new notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/notification.png')}}" alt="">
                {{\App\CentralLogics\translate('notification')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('title')}}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('New notification') }}" required maxlength="100">
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('description')}}</label>
                                <textarea name="description" class="form-control" required maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <label class="mb-0">{{translate('Image')}}</label>
                                    <small class="text-danger">* ( Ratio 1:1 )</small>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    <div class="upload-file">
                                        <input type="file" name="image" id="customFileEg1" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                               class="upload-file__input" required>
                                        <div class="upload-file__img">
                                            <img width="150" id="viewer" src="{{asset('public/assets/admin/img/icons/upload_img.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{\App\CentralLogics\translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('send_notification')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{\App\CentralLogics\translate('notification_table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $notifications->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                            <form action="#" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search by Title')}}" aria-label="Search"
                                        value="" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                        <th>{{\App\CentralLogics\translate('image')}}</th>
                        <th>{{\App\CentralLogics\translate('title')}}</th>
                        <th>{{\App\CentralLogics\translate('description')}}</th>
                        <th>{{\App\CentralLogics\translate('status')}}</th>
                        <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($notifications as $key=>$notification)
                        <tr>
                            <td>{{$notifications->firstitem()+$key}}</td>
                            <td>
                                @if($notification['image']!=null)
                                    <div class="avatar-lg border rounded">
                                        <img class="img-fit rounded" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                            src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}">
                                    </div>
                                @else
                                    <label class="badge badge-soft-warning">{{translate('No')}} {{\App\CentralLogics\translate('image')}}</label>
                                @endif
                            </td>
                            <td>
                                {{substr($notification['title'],0,25)}} {{strlen($notification['title'])>25?'...':''}}
                            </td>
                            <td>
                                {{substr($notification['description'],0,25)}} {{strlen($notification['description'])>25?'...':''}}
                            </td>
                            <td>
                                @if($notification['status']==1)
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" checked id="{{$notification['id']}}"
                                               onclick="location.href='{{route('admin.notification.status',[$notification['id'],0])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @else
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input"  id="{{$notification['id']}}"
                                               onclick="location.href='{{route('admin.notification.status',[$notification['id'],1])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info square-btn"
                                        href="{{route('admin.notification.edit',[$notification['id']])}}"><i class="tio tio-edit"></i></a>
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="$('#notification-{{$notification['id']}}').submit()"><i class="tio tio-delete"></i></a>
                                </div>
                                <form
                                    action="{{route('admin.notification.delete',[$notification['id']])}}"
                                    method="post" id="notification-{{$notification['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $notifications->links() !!}
                </div>
            </div>
            @if(count($notifications)==0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
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
