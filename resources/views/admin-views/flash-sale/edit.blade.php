@extends('layouts.admin.app')

@section('title', translate('Update Flash sale'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="16" src="{{asset('public/assets/admin/img/icons/flash-sale.png')}}" alt="">
                {{\App\CentralLogics\translate('Update Flash sale')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.flash-sale.update', [$flash_sale['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{translate('Title')}}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('Ex : LUX') }}"
                                       value="{{ $flash_sale['title'] }}" required maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{ translate('Start Date')}}</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                       value="{{ date('Y-m-d\TH:i', strtotime($flash_sale['start_date']))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{translate('End Date')}}</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                       value="{{ date('Y-m-d\TH:i', strtotime($flash_sale['end_date']))}}" required>
                            </div>
                        </div>
{{--                        <div class="col-sm-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>--}}
{{--                                    <div class="custom_upload_input max-h200px ratio-3">--}}
{{--                                        <input type="file" name="image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"--}}
{{--                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">--}}

{{--                                        <div class="img_area_with_preview position-absolute z-index-2">--}}
{{--                                            <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">--}}
{{--                                        </div>--}}
{{--                                        <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center existing-image-div">--}}
{{--                                            <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">--}}
{{--                                                <img  src="{{asset('storage/app/public/flash-sale')}}/{{$flash_sale['image']}}" class="w-100">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Images Ratio') }} 3:1</p>--}}
{{--                                    <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary px-5">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('script_2')
    <script>
        let oldStartDate = '{{ date('Y-m-d\TH:i', strtotime($flash_sale['start_date'])) }}';
        let oldEndDate = '{{ date('Y-m-d\TH:i', strtotime($flash_sale['end_date'])) }}';

        $('#start_date, #end_date').change(function () {
            let from = $('#start_date').val();
            let to = $('#end_date').val();
            if (from != '' && to != '') {
                if (from > to) {
                    $('#start_date').val(oldStartDate);
                    $('#end_date').val(oldEndDate);
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
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
@endpush
