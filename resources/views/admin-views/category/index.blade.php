@extends('layouts.admin.app')

@section('title', \App\CentralLogics\translate('Add new category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/brand-setup.png')}}" alt="">
                {{\App\CentralLogics\translate('category_Setup')}}
            </h2>
        </div>


        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Model\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'en')
                    @if ($language)
                    @php($default_lang = json_decode($language)[0])
                    <ul class="nav nav-tabs mb-4 max-content">
                        @foreach (json_decode($language) as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}" href="#"
                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="row">
                        <div class="col-12">
                            @foreach (json_decode($language) as $lang)
                                <div class="form-group {{ $lang != $default_lang ? 'd-none' : '' }} lang_form"  id="{{ $lang }}-form">
                                    <label class="input-label">
                                        {{ \App\CentralLogics\translate('name') }} ({{ strtoupper($lang) }})
                                    </label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{ \App\CentralLogics\translate('New Category') }}" maxlength="255"
                                            {{ $lang == $default_lang ? 'required' : '' }} oninvalid="document.getElementById('en-link').click()">
                                </div>
                                <input type="hidden" name="lang[]" value="{{ $lang }}">
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group lang_form" id="{{ $default_lang }}-form">
                                        <label class="input-label">
                                            {{ \App\CentralLogics\translate('name') }} ({{ strtoupper($lang) }})
                                        </label>
                                        <input type="text" name="name[]" class="form-control" maxlength="255"
                                                placeholder="{{ \App\CentralLogics\translate('New Category') }}" required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $default_lang }}">
                                    @endif
                                    <input name="position" value="0" style="display: none">
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                        <div class="custom_upload_input ratio-1 max-w-200">
                                            <input type="file" name="image" class="custom-upload-input-file meta-img h-100" id="" data-imgpreview="pre_meta_image_viewer"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_meta_image_viewer" class="h-auto aspect-1 bg-white ratio-1" src="img" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
{{--                                                    <img src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}" class="mw-100">--}}
                                                    <h3 class="text-muted">{{ translate('Drag & Drop here') }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Images Ratio') }} 1:1</p>
                                        <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label class="mb-2">{{\App\CentralLogics\translate('Banner Image')}}</label>
                                        <div class="custom_upload_input max-h200px ratio-8">
                                            <input type="file" name="banner_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
{{--                                                    <img src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" class="mw-100">--}}
                                                    <h3 class="text-muted">{{ translate('Drag & Drop here') }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Banner Images Ratio') }} 8:1</p>
                                        <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <button type="reset" class="btn btn-secondary px-5">{{\App\CentralLogics\translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary px-5">{{\App\CentralLogics\translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-lg-8 col-sm-4 col-md-6">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{\App\CentralLogics\translate('Category Table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $categories->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-lg-4 col-sm-8 col-md-6">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                       class="form-control"
                                       placeholder="{{translate('Search by Category')}}" aria-label="Search"
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

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{\App\CentralLogics\translate('SL')}}</th>
                            <th>{{\App\CentralLogics\translate('Category_Image')}}</th>
                            <th>{{\App\CentralLogics\translate('name')}}</th>
                            <th>{{\App\CentralLogics\translate('Is Featured')}} ? <i class="tio-info-outined cursor-pointer" data-toggle="tooltip" title="{{ translate('If enable, the category will show in featured category') }}"></i></th>
                            <th>{{\App\CentralLogics\translate('status')}}</th>
                            <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($categories as $key=>$category)
                        <tr>
                            <td>{{$categories->firstItem()+$key}}</td>
                            <td>
                                <div class="avatar-lg rounded border">
                                    <img class="img-fit rounded" src="{{asset('storage/app/public/category')}}/{{$category['image']}}" onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="">
                                </div>
                            </td>
                            <td>{{$category['name']}}</td>
                            <td>
                                <label class="on-off-toggle">
                                    <input class="on-off-toggle__input" type="checkbox"
                                        {{$category['is_featured']==1? 'checked' : ''}}
                                        onclick="location.href='{{route('admin.category.featured',[$category['id'], $category->is_featured == 1 ? 0: 1])}}'">
                                    <span class="on-off-toggle__slider"></span>
                                </label>
                            </td>
                            <td>
                                @if($category['status']==1)
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" {{$category['status']==1? 'checked' : ''}} id="{{$category['id']}}"
                                               onclick="location.href='{{route('admin.category.status',[$category['id'],0])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @else
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" {{$category['status']==1? 'checked' : ''}} id="{{$category['id']}}"
                                               onclick="location.href='{{route('admin.category.status',[$category['id'],1])}}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info square-btn" href="{{route('admin.category.edit',[$category['id']])}}">
                                        <i class="tio tio-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="form_alert('category-{{$category['id']}}','{{\App\CentralLogics\translate('Want to delete this ?')}}')"><i class="tio tio-delete"></i></a>
                                </div>
                                <form action="{{route('admin.category.delete',[$category['id']])}}"
                                        method="post" id="category-{{$category['id']}}">
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
                    {!! $categories->links() !!}
                </div>
            </div>
            @if(count($categories)==0)
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

    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>

    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer2');
        });
    </script>

    <script>

        {{--function featured_status(url) {--}}
        {{--    Swal.fire({--}}
        {{--        title: '{{ translate("Are you sure?") }}',--}}
        {{--        text:  '',--}}
        {{--        type: 'warning',--}}
        {{--        showCancelButton: true,--}}
        {{--        cancelButtonColor: 'default',--}}
        {{--        confirmButtonColor: '#107980',--}}
        {{--        confirmButtonText: '{{ translate("Yes") }}',--}}
        {{--        cancelButtonText: '{{ translate("No") }}',--}}
        {{--        reverseButtons: true--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.value) {--}}
        {{--            location.href = url;--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    </script>
@endpush
