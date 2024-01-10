@extends('layouts.admin.app')

@section('title', translate('Update banner'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/banner.png')}}" alt="">
                {{\App\CentralLogics\translate('update_banner')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.banner.update',[$banner['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf @method('put')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('title')}}</label>
                                <input type="text" name="title" value="{{$banner['title']}}" class="form-control"
                                       placeholder="{{ translate('New banner') }}" required>
                            </div>
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('Banner')}} {{\App\CentralLogics\translate('type')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="banner_type" class="form-control" onchange="banner_select(this.value)">
                                    <option value="primary" {{ $banner['banner_type'] == 'primary' ? 'selected' : '' }}>{{\App\CentralLogics\translate('Primary Banner')}}</option>
                                    <option value="secondary" {{ $banner['banner_type'] == 'secondary' ? 'selected' : '' }}>{{\App\CentralLogics\translate('Secondary Banner')}}</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('Redirection')}} {{\App\CentralLogics\translate('type')}}<span
                                        class="input-label-secondary">*</span></label>
                                <select name="item_type" class="form-control" onchange="show_item(this.value)">
                                    <option value="product" {{$banner['product_id']==null?'':'selected'}}>{{\App\CentralLogics\translate('product')}}</option>
                                    <option value="category" {{$banner['category_id']==null?'':'selected'}}>{{\App\CentralLogics\translate('category')}}</option>
                                </select>
                            </div>

                            <div class="mb-5" id="type-product"
                                 style="display: {{$banner['product_id']==null?'none':'block'}}">
                                <label class="input-label" for="exampleFormControlSelect1">{{\App\CentralLogics\translate('product')}} <span
                                        class="input-label-secondary">*</span></label>
                                <select name="product_id" class="form-control js-select2-custom">
                                    @foreach($products as $product)
                                        <option
                                            value="{{$product['id']}}" {{$banner['product_id']==$product['id']?'selected':''}}>
                                            {{$product['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-5" id="type-category"
                                 style="display: {{$banner['category_id']==null?'none':'block'}}">
                                <label class="input-label" for="exampleFormControlSelect1">{{\App\CentralLogics\translate('category')}} <span
                                        class="input-label-secondary">*</span></label>
                                <select name="category_id" class="form-control js-select2-custom">
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}" {{$banner['category_id']==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="primary_banner" style="display: {{ $banner->banner_type != 'primary' ? 'none': '' }}">
                                <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                <div class="custom_upload_input max-h200px ratio-2">
                                    <input type="file" name="primary_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center existing-image-div">
                                        <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
                                            <img  src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}" class="w-100">
                                        </div>
                                    </div>
                                </div>

                                <p class="fs-16 mb-2 text-dark mt-2">
                                    <i class="tio-info-outined cursor-pointer" data-toggle="tooltip"
                                       title="{{ translate('When do not have secondary banner than the primary banner ration will be 3:1') }}">
                                    </i>
                                    {{ translate('Images Ratio') }} 2:1
                                </p>
                                <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>
                            </div>

                            <div class="form-group" id="secondary_banner" style="display: {{ $banner->banner_type != 'secondary' ? 'none': '' }}">
                                <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                <div class="custom_upload_input max-h200px ratio-1">
                                    <input type="file" name="secondary_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center existing-image-div">
                                        <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
                                            <img  src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}" class="w-100">
                                        </div>
                                    </div>
                                </div>

                                <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Banner Images Ratio') }} 1:1</p>
                                <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>
                            </div>

                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary px-5">{{\App\CentralLogics\translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary px-5">{{\App\CentralLogics\translate('update')}}</button>
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

        function show_item(type) {
            if (type === 'product') {
                $("#type-product").show();
                $("#type-category").hide();
            } else {
                $("#type-product").hide();
                $("#type-category").show();
            }
        }

        function banner_select(type) {
            if (type == 'primary') {
                $("#primary_banner").show();
                $("#secondary_banner").hide();
            } else {
                $("#primary_banner").hide();
                $("#secondary_banner").show();
            }
        }
    </script>
    <script>
        $('.delete_file_input').click(function () {
            let $parentDiv = $(this).closest('div');
            $parentDiv.find('input[type="file"]').val('');
            $parentDiv.find('.img_area_with_preview img').attr("src", " ");
            $(this).hide();
        });

        $('.custom-upload-input-file').on('change', function(){
            if (parseFloat($(this).prop('files').length) != 0) {
                let $parentDiv = $(this).closest('div');
                $parentDiv.find('.delete_file_input').fadeIn();
            }
        })


        function uploadColorImage($parentDiv, thisData) {
            if (thisData && thisData[0].files.length > 0) {
                $parentDiv.find('.img_area_with_preview img').attr("src", window.URL.createObjectURL(thisData[0].files[0]));
                $parentDiv.find('.img_area_with_preview img').removeClass('d-none');
                $parentDiv.find('.existing-image-div img').addClass('d-none');
                $parentDiv.find('.delete_file_input').fadeIn();
            }
        }

        $('.custom-upload-input-file').on('change', function () {
            let $parentDiv = $(this).closest('div');
            uploadColorImage($parentDiv, $(this));
        });

    </script>
@endpush
