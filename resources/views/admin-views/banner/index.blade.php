@extends('layouts.admin.app')

@section('title', translate('Add new banner'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="24" src="{{asset('public/assets/admin/img/icons/banner.png')}}" alt="">
                {{\App\CentralLogics\translate('add_new_banner')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.banner.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('title')}}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('New banner') }}" required maxlength="100">
                            </div>
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('Banner')}} {{\App\CentralLogics\translate('type')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="banner_type" class="form-control" onchange="banner_select(this.value)">
                                    <option value="primary">{{\App\CentralLogics\translate('Primary Banner')}}</option>
                                    <option value="secondary">{{\App\CentralLogics\translate('Secondary Banner')}}</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label class="input-label">{{\App\CentralLogics\translate('Redirection')}} {{\App\CentralLogics\translate('type')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="item_type" class="form-control" onchange="show_item(this.value)">
                                    <option value="product">{{\App\CentralLogics\translate('product')}}</option>
                                    <option value="category">{{\App\CentralLogics\translate('category')}}</option>
                                </select>
                            </div>
                            <div class="mb-5" id="type-product">
                                <label class="input-label">
                                    {{\App\CentralLogics\translate('product')}}
                                    <span class="input-label-secondary text-danger">*</span>
                                </label>
                                <select name="product_id" class="form-control js-select2-custom">
                                    @foreach($products as $product)
                                        <option value="{{$product['id']}}">{{$product['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-5" id="type-category" style="display: none">
                                <label class="input-label">
                                    {{\App\CentralLogics\translate('category')}}
                                    <span class="input-label-secondary text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-control js-select2-custom">
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group" id="primary_banner">
                                <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                <div class="custom_upload_input max-h200px ratio-2">
                                    <input type="file" name="primary_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
                                            <h3 class="text-muted">{{ translate('Drag & Drop here') }}</h3>
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

                            <div class="form-group" id="secondary_banner" style="display: none">
                                <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                <div class="custom_upload_input max-h200px ratio-1">
                                    <input type="file" name="secondary_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
                                            <h3 class="text-muted">{{ translate('Drag & Drop here') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Images Ratio') }} 1:1</p>
                                <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary px-5">{{\App\CentralLogics\translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary px-5">{{\App\CentralLogics\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card -->
        <div class="card mt-3">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{\App\CentralLogics\translate('banner_table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $banners->count() }}</span>
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
                $parentDiv.find('.delete_file_input').fadeIn();
            }
        }

        $('.custom-upload-input-file').on('change', function () {
            let $parentDiv = $(this).closest('div');
            uploadColorImage($parentDiv, $(this));
        });

    </script>
@endpush
