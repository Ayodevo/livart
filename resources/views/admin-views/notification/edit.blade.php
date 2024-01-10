@extends('layouts.admin.app')

@section('title', translate('Update Notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/notification.png')}}" alt="">
                {{\App\CentralLogics\translate('update_notification')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.notification.update',[$notification['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('title')}}</label>
                                <input type="text" value="{{$notification['title']}}" name="title" class="form-control" placeholder="{{ translate('New notification') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('description')}}</label>
                                <textarea name="description" class="form-control" required>{{$notification['description']}}</textarea>
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
                                        <input type="file" id="customFileEg1" name="image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                               class="upload-file__input">
                                        <div class="upload-file__img">
                                            <img width="150" id="viewer" onerror="this.src='{{asset('public/assets/admin/img/icons/upload_img.png')}}'"
                                                 src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{\App\CentralLogics\translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
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
