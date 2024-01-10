@extends('layouts.admin.app')

@section('title', translate('Product List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/product.png')}}" alt="">
                {{\App\CentralLogics\translate('product_list')}}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{$products->total()}}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{url()->current()}}" method="GET">
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
                            <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                <a href="{{route('admin.product.add-new')}}" class="btn btn-primary">
                                    <i class="tio-add"></i>
                                    {{\App\CentralLogics\translate('add_new_product')}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CentralLogics\translate('SL')}}</th>
                                    <th>{{\App\CentralLogics\translate('product_name')}}</th>
                                    <th>{{\App\CentralLogics\translate('status')}}</th>
                                    <th>{{\App\CentralLogics\translate('price')}}</th>
                                    <th>{{\App\CentralLogics\translate('stock')}}</th>
                                    <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($products as $key=>$product)
                                <tr>
                                    <td>{{$products->firstitem()+$key}}</td>
                                    <td>
                                        <span class="media gap-3 align-items-center">
                                            <div class="avatar rounded border">
                                                <img
                                                    src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'],true)[0]}}"
                                                    class="img-fit rounded"
                                                    onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                                            </div>
                                             <a href="{{route('admin.product.view',[$product['id']])}}" class="media-body text-dark">
                                               {{substr($product['name'],0,20)}}{{strlen($product['name'])>20?'...':''}}
                                             </a>
                                        </span>
                                    </td>
                                    <td>
                                        @if($product['status']==1)
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input" checked id="{{$product['id']}}"
                                                       onclick="location.href='{{route('admin.product.status',[$product['id'],0])}}'">
                                                <span class="switcher_control"></span>
                                            </label>
                                        @else
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input"  id="{{$product['id']}}"
                                                       onclick="location.href='{{route('admin.product.status',[$product['id'],1])}}'">
                                                <span class="switcher_control"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>{{ Helpers::set_symbol($product['price']) }}</td>
                                    <td>
                                        <label class="badge badge-soft-info fs-14">{{$product['total_stock']}}</label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a class="btn btn-outline-primary square-btn"
                                                href="{{route('admin.product.edit',[$product['id']])}}">
                                                <i class="tio tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                onclick="form_alert('product-{{$product['id']}}','{{\App\CentralLogics\translate('Want to delete this item ?')}}')"><i class="tio tio-delete"></i></a>
                                        </div>
                                        <form action="{{route('admin.product.delete',[$product['id']])}}"
                                                method="post" id="product-{{$product['id']}}">
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
                            {!! $products->links() !!}
                        </div>
                    </div>
                    @if(count($products)==0)
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
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.search')}}',
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
