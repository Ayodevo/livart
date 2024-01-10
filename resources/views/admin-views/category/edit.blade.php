@extends('layouts.admin.app')

@section('title', \App\CentralLogics\translate('Update category'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/brand-setup.png')}}" alt="">
                @if($category->parent_id == 0)
                    {{\App\CentralLogics\translate('category_update')}}
                @else
                    {{\App\CentralLogics\translate('sub_category_update')}}
                @endif

            </h2>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('admin.category.update',[$category['id']])}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('key','language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')
                            @if($language)
                                @php($default_lang = json_decode($language)[0])
                                <ul class="nav nav-tabs mb-4 max-content">
                                    @foreach(json_decode($language) as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#"
                                               id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        @foreach(json_decode($language) as $lang)
                                            <?php
                                            if (count($category['translations'])) {
                                                $translate = [];
                                                foreach ($category['translations'] as $t) {
                                                    if ($t->locale == $lang && $t->key == "name") {
                                                        $translate[$lang]['name'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="form-group {{$lang != $default_lang ? 'd-none':''}} lang_form"
                                                 id="{{$lang}}-form">
                                                <label class="input-label"
                                                       for="exampleFormControlInput1">{{\App\CentralLogics\translate('name')}}
                                                    ({{strtoupper($lang)}})</label>
                                                <input type="text" name="name[]" maxlength="255"
                                                       value="{{$lang==$default_lang?$category['name']:($translate[$lang]['name']??'')}}"
                                                       class="form-control" oninvalid="document.getElementById('en-link').click()"
                                                       placeholder="{{ \App\CentralLogics\translate('New Category') }}" {{$lang == $default_lang? 'required':''}}>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                        @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group lang_form" id="{{$default_lang}}-form">
                                                        <label class="input-label"
                                                               for="exampleFormControlInput1">{{\App\CentralLogics\translate('name')}}
                                                            ({{strtoupper($lang)}})</label>
                                                        <input type="text" name="name[]" value="{{$category['name']}}"
                                                               class="form-control" oninvalid="document.getElementById('en-link').click()"
                                                               placeholder="{{ \App\CentralLogics\translate('New Category') }}" required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                                    @endif
                                                    <input name="position" value="0" style="display: none">
                                                </div>
                                                @if($category->parent_id == 0)
                                                    <div class="col-md-4">

                                                        <div class="form-group">
                                                            <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>
                                                            <div class="custom_upload_input ratio-1 max-w-200">
                                                                <input type="file" name="image" class="custom-upload-input-file meta-img h-100" id="" data-imgpreview="pre_meta_image_viewer"
                                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                                                <div class="img_area_with_preview position-absolute z-index-2">
                                                                    <img id="pre_meta_image_viewer" class="h-auto aspect-1 bg-white ratio-1" src="img" onerror="this.classList.add('d-none')">
                                                                </div>
                                                                <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                                        <img src="{{asset('storage/app/public/category')}}/{{$category['image']}}" class="w-100">
{{--                                                                        <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>--}}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Images Ratio') }} 1:1</p>
                                                            <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>                                                      </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="mb-2">{{\App\CentralLogics\translate('Banner Image')}}</label>
                                                            <div class="custom_upload_input max-h200px ratio-8">
                                                                <input type="file" name="banner_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                                                <div class="img_area_with_preview position-absolute z-index-2">
                                                                    <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                                                </div>
                                                                <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center existing-image-div">
                                                                    <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">
                                                                        <img  src="{{asset('storage/app/public/category/banner')}}/{{$category['banner_image']}}" class="w-100">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Banner Images Ratio') }} 8:1</p>
                                                            <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>

                                                        </div>

                                                    </div>
                                                @else
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="input-label"
                                                                   for="exampleFormControlSelect1">{{\App\CentralLogics\translate('main')}} {{\App\CentralLogics\translate('category')}}</label>
                                                            <select id="exampleFormControlSelect1" name="parent_id" class="form-control" required>
                                                                @foreach(\App\Model\Category::where(['position'=>0])->get() as $main_category)
                                                                    <option value="{{$main_category['id']}}" {{ $main_category['id'] == $category['parent_id'] ? 'selected' : ''}}>{{$main_category['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="reset" class="btn btn-secondary">{{\App\CentralLogics\translate('reset')}}</button>
                                            <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('update')}}</button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });
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
@endpush
