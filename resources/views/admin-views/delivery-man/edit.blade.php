@extends('layouts.admin.app')

@section('title', translate('Update delivery-man'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/deliveryman.png')}}" alt="">
                {{\App\CentralLogics\translate('update_Deliveryman')}}
            </h2>
        </div>

        <form action="{{route('admin.delivery-man.update',[$delivery_man['id']])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="tio-user"></i>
                        {{\App\CentralLogics\translate('general_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('first')}} {{\App\CentralLogics\translate('name')}}</label>
                                <input type="text" value="{{$delivery_man['f_name']}}" name="f_name"
                                       class="form-control" placeholder="{{ translate('New delivery-man') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('last')}} {{\App\CentralLogics\translate('name')}}</label>
                                <input type="text" value="{{$delivery_man['l_name']}}" name="l_name"
                                       class="form-control" placeholder="{{ translate('Last Name') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('phone')}}</label>
                                <input type="text" name="phone" value="{{$delivery_man['phone']}}" class="form-control"
                                       placeholder="{{ translate('Ex : 017********') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('branch')}}</label>
                                <select name="branch_id" class="form-control">
                                    <option value="0" {{$delivery_man['branch_id']==0?'selected':''}}>{{\App\CentralLogics\translate('all')}}</option>
                                    @foreach(\App\Model\Branch::all() as $branch)
                                        <option value="{{$branch['id']}}" {{$delivery_man['branch_id']==$branch['id']?'selected':''}}>{{$branch['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('identity')}} {{\App\CentralLogics\translate('type')}}</label>
                                <select name="identity_type" class="form-control">
                                    <option
                                        value="passport" {{$delivery_man['identity_type']=='passport'?'selected':''}}>
                                        {{\App\CentralLogics\translate('passport')}}
                                    </option>
                                    <option
                                        value="driving_license" {{$delivery_man['identity_type']=='driving_license'?'selected':''}}>
                                        {{\App\CentralLogics\translate('driving')}} {{\App\CentralLogics\translate('license')}}
                                    </option>
                                    <option value="nid" {{$delivery_man['identity_type']=='nid'?'selected':''}}>{{\App\CentralLogics\translate('nid')}}
                                    </option>
                                    <option
                                        value="restaurant_id" {{$delivery_man['identity_type']=='restaurant_id'?'selected':''}}>
                                        {{\App\CentralLogics\translate('store')}} {{\App\CentralLogics\translate('id')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('identity')}} {{\App\CentralLogics\translate('number')}}</label>
                                <input type="text" name="identity_number" value="{{$delivery_man['identity_number']}}"
                                       class="form-control"
                                       placeholder="{{ translate('Ex : DH-23434-LS') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <center class="mb-3">
                                    <img class="upload-img-view" id="viewer"
                                         src="{{asset('storage/app/public/delivery-man').'/'.$delivery_man['image']}}" alt="delivery-man image"/>
                                </center>
                                <label>{{\App\CentralLogics\translate('deliveryman')}} {{\App\CentralLogics\translate('image')}}</label>
                                <small class="text-danger">* ( {{\App\CentralLogics\translate('ratio')}} 1:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('identity')}} {{\App\CentralLogics\translate('image')}}</label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <label class="input-label">{{\App\CentralLogics\translate('identity')}} {{\App\CentralLogics\translate('images')}} : </label> --}}
                                @foreach(json_decode($delivery_man['identity_image'],true) as $img)
                                    <div class="col-6 col-sm-4 col-md-6">
                                        <div><img class="mx-w100 max-h150px" src="{{asset('storage/app/public/delivery-man').'/'.$img}}"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="tio-user"></i>
                        {{\App\CentralLogics\translate('account_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('email')}}</label>
                                <input type="email" value="{{$delivery_man['email']}}" name="email" class="form-control"
                                       placeholder="{{ translate('Ex : ex@example.com') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" class="js-toggle-password form-control input-field"
                                           placeholder="{{ translate('Password minimum 6 characters') }}"
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
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{\App\CentralLogics\translate('confirm_Password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password_confirmation" class="js-toggle-password form-control input-field"
                                           placeholder="{{ translate('Password minimum 6 characters') }}"
                                           data-hs-toggle-password-options='{
                                        "target": "#changeConPassTarget",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#changeConPassIcon"
                                        }'>
                                    <div id="changeConPassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changeConPassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{\App\CentralLogics\translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
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

    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-6 col-sm-4 col-md-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("File size too big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
