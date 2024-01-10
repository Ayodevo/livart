@extends('layouts.admin.app')

@section('title', translate('Review List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/product-review.png')}}" alt="">
                {{\App\CentralLogics\translate('review_list')}}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-sm-4">
                                <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                                    {{\App\CentralLogics\translate('review_table')}}
                                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $reviews->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8">
                                <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                                    <form action="#" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search"
                                                class="form-control"
                                                placeholder="{{translate('Search by Product Name')}}" aria-label="Search"
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
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CentralLogics\translate('SL')}}</th>
                                <th>{{\App\CentralLogics\translate('product')}}</th>
                                <th>{{\App\CentralLogics\translate('customer')}}</th>
                                <th>{{\App\CentralLogics\translate('review')}}</th>
                                <th class="text-center">{{\App\CentralLogics\translate('rating')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($reviews as $key=>$review)
                                    <tr>
                                        <td>{{$reviews->firstitem()+$key}}</td>
                                        <td>
                                             @if($review->product)
                                                <a class="text-dark" href="{{route('admin.product.view',[$review['product_id']])}}">
                                                    {{ $review->product['name'] }}
                                                </a>
                                            @else
                                                <span class="text-muted">
                                                    {{\App\CentralLogics\translate('Product unavailable')}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($review->customer))
                                                <a class="text-dark" href="{{route('admin.customer.view',[$review->user_id])}}">
                                                    {{$review->customer->f_name." ".$review->customer->l_name}}
                                                </a>
                                            @else
                                                <span class="text-muted">
                                                    {{translate('customer_unavailable')}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mx-w300 mn-w200 text-wrap">
                                                {{$review->comment}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <label class="badge badge-soft-info d-flex gap-1 align-items-center justify-content-center">
                                                    {{$review->rating}} <i class="tio-star"></i>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $reviews->links() !!}
                        </div>
                    </div>
                    @if(count($reviews)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.reviews.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
