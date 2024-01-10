@extends('layouts.admin.app')

@section('title', translate('Flash sale'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="16" src="{{asset('public/assets/admin/img/icons/flash-sale.png')}}" alt="">
                {{\App\CentralLogics\translate('Flash sale')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.flash-sale.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{translate('Title')}}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('Ex : LUX') }}" required maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{ translate('Start Date')}}</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="input-label">{{translate('End Date')}}</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>

{{--                        <div class="col-sm-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="mb-2">{{\App\CentralLogics\translate('Image')}}</label>--}}
{{--                                <div class="custom_upload_input max-h200px ratio-3">--}}
{{--                                    <input type="file" name="image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"--}}
{{--                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">--}}

{{--                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">--}}
{{--                                                <i class="tio-delete"></i>--}}
{{--                                            </span>--}}

{{--                                    <div class="img_area_with_preview position-absolute z-index-2">--}}
{{--                                        <img id="pre_meta_image_viewer" class="aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">--}}
{{--                                    </div>--}}
{{--                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">--}}
{{--                                        <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden">--}}
{{--                                            --}}{{--                                                    <img src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" class="mw-100">--}}
{{--                                            <h3 class="text-muted">{{ translate('Drag & Drop here') }}</h3>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <p class="fs-16 mb-2 text-dark mt-2">{{ translate('Images Ratio') }} 3:1</p>--}}
{{--                                <p class="fs-14 text-muted mb-0">{{ translate('Image format : jpg, png, jpeg | Maximum Size') }} : 2 MB</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary px-5">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card -->
        <div class="card mt-4">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{translate('Flash sale Table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $flash_sales->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                            <form  action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control" value="{{ $search }}"
                                        placeholder="{{translate('Search by Title')}}" aria-label="Search"
                                           required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">{{translate('search')}}
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
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('Title')}}</th>
                            <th>{{translate('Duration')}}</th>
                            <th>{{translate('status')}}</th>
                            <th>{{translate('Active Products')}}</th>
                            <th>{{translate('Publish')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($flash_sales as $key => $flash_sale)
                        <tr>
                            <td> {{$flash_sales->firstitem()+$key}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-25">
                                    {{$flash_sale['title']}}
                                </span>
                            </td>
                            <td>{{date('Y-m-d H:i A',strtotime($flash_sale['start_date']))}} - {{date('Y-m-d H:i A',strtotime($flash_sale['end_date']))}}</td>
                            <td>
                                @if(\Carbon\Carbon::parse($flash_sale['end_date'])->endOfDay()->isPast())
                                    <span class="badge badge-soft-danger">{{ translate('expired')}} </span>
                                @else
                                    <span class="badge badge-soft-success"> {{ translate('active')}} </span>
                                @endif
                            </td>
                            <td>{{  $flash_sale->products_count }}</td>
                            <td>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input"
                                           onclick="status_change_alert('{{ route('admin.flash-sale.status', [$flash_sale->id, $flash_sale->status ? 0 : 1]) }}', '{{ $flash_sale->status? translate('you_want_to_disable_this'): translate('you_want_to_active_this') }}', event)"
                                        {{ $flash_sale->status ? 'checked' : '' }} >
                                    <span class="switcher_control"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-soft-primary btn-sm py-1" href="{{route('admin.flash-sale.add-product', [$flash_sale['id']])}}">
                                        <i class="tio tio-add"></i> {{ translate('Add Product') }}
                                    </a>
                                    <a class="btn btn-outline-info square-btn" href="{{route('admin.flash-sale.edit',[$flash_sale['id']])}}">
                                        <i class="tio tio-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                       onclick="form_alert('deal-{{$flash_sale['id']}}','{{ translate("Want to delete this") }}')">
                                        <i class="tio tio-delete"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.flash-sale.delete',[$flash_sale['id']])}}"
                                      method="post" id="deal-{{$flash_sale['id']}}">
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
                    {!! $flash_sales->links() !!}
                </div>
            </div>
             @if(count($flash_sales)==0)
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
        $('#start_date, #end_date').change(function () {
            let from = $('#start_date').val();
            let to = $('#end_date').val();
            if (from != '' && to != '') {
                if (from > to) {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        });

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
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
