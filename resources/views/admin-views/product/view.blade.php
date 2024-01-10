@extends('layouts.admin.app')

@section('title', translate('Product Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="mb-3">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('public/assets/admin/img/icons/product.png')}}" alt="">
                    {{$product['name']}}
                </h2>
                <a href="{{url()->previous()}}" class="btn btn-primary">
                    <i class="tio-back-ui"></i> {{\App\CentralLogics\translate('back')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3">
            <!-- Body -->
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-5">
                        <div class="media gap-4 align-items-center">
                            <div class="avatar avatar-xxl avatar-4by3 border rounded">
                                <img class="img-fit rounded"
                                src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'],true)[0]}}"
                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                alt="Image Description">
                            </div>
                            <div class="media-body">
                                <h2 class="display-2 text-primary mb-0">
                                    {{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}
                                </h2>
                                <p> {{\App\CentralLogics\translate('of')}} {{$product->reviews->count()}} {{\App\CentralLogics\translate('reviews')}}
                                    <span class="badge badge-soft-dark badge-pill ml-1"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <ul class="list-unstyled list-unstyled-py-3 mb-0">
                        @php($total=$product->reviews->count())
                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CentralLogics\Helpers::rating_count($product['id'],5))
                                <span
                                    class="mr-3">{{translate('5 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CentralLogics\Helpers::rating_count($product['id'],4))
                                <span class="mr-3">{{translate('4 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CentralLogics\Helpers::rating_count($product['id'],3))
                                <span class="mr-3">{{translate('3 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CentralLogics\Helpers::rating_count($product['id'],2))
                                <span class="mr-3">{{translate('2 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CentralLogics\Helpers::rating_count($product['id'],1))
                                <span class="mr-3">{{translate('1 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>

                    <div class="col-12">
                        <hr>
                    </div>

                    <div class="col-md-6 col-lg-4 text-dark">
                        <h4 class="mb-3 text-capitalize">{{$product['name']}}</h4>
                        <div>
                            {{\App\CentralLogics\translate('total_stock')}}
                            : {{$product['total_stock']}}
                        </div>
                        <div>
                            {{\App\CentralLogics\translate('price')}} :
                            <span>{{ Helpers::set_symbol($product['price']) }} / {{\App\CentralLogics\translate(''.$product['unit'])}}</span>
                        </div>
                        <div>{{\App\CentralLogics\translate('tax')}} :
{{--                            <span>{{ Helpers::set_symbol(\App\CentralLogics\Helpers::tax_calculate($product,$product['price'])) }}</span>--}}
                            <span>{{ $product['tax_type'] == 'amount' ? Helpers::set_symbol($product['tax']) : $product['tax']. '%' }}</span>
                        </div>
                        <div>{{\App\CentralLogics\translate('discount')}} :
{{--                            <span>{{ Helpers::set_symbol(\App\CentralLogics\Helpers::discount_calculate($product,$product['price'])) }}</span>--}}
                            <span>{{ $product['discount_type'] == 'amount' ? Helpers::set_symbol($product['discount']) : $product['discount']. '%'}}</span>
                        </div>
                        @if(count(json_decode($product['variations'],true)) > 1)
                            <h4 class="mt-4 mb-3 text-capitalize"> {{\App\CentralLogics\translate('variations')}} </h4>
                        @endif
                        <div class="d-flex flex-column gap-1 fs-12">
                            @foreach(json_decode($product['variations'],true) as $variation)
                                <div class="text-capitalize">
                                {{$variation['type']}} : {{ Helpers::set_symbol($variation['price']) }} ( Stock : {{$variation['stock']??0}} )
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-8">
                        <div class="border-md-left pl-md-4 h-100">
                            <h4>{{\App\CentralLogics\translate('short')}} {{\App\CentralLogics\translate('description')}} : </h4>
                            <p>{!! $product['description'] !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="card">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{\App\CentralLogics\translate('reviewer')}}</th>
                        <th>{{\App\CentralLogics\translate('review')}}</th>
                        <th>{{\App\CentralLogics\translate('date')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if(isset($review->customer))
                                    <a class="media gap-3 align-items-center"
                                       href="{{route('admin.customer.view',[$review['user_id']])}}">
                                        <div class="avatar avatar-circle">
                                            <img class="img-fit rounded-circle"
                                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                                class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                                title="Verified Customer"></i></span>
                                            <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted">
                                        {{translate('Customer unavailable')}}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap mx-w300 mn-w200">
                                    <div class="d-flex">
                                        <label class="badge badge-soft-info d-flex gap-1 align-items-center">
                                            {{$review->rating}} <i class="tio-star"></i>
                                        </label>
                                    </div>

                                    <div>
                                        {{$review['comment']}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($review['created_at']))}}
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
                    {!! $reviews->links() !!}
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $('.ql-hidden').hide()
    </script>
@endpush
