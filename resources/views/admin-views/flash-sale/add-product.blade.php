@extends('layouts.admin.app')

@section('title', translate('Flash sale'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="16" src="{{asset('public/assets/admin/img/icons/flash-sale.png')}}" alt="">
                {{translate('Flash sale Setup')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="mb-4 product-search-result-wrap">
                    <label class="input-label">{{translate('Product')}}</label>
                    <input type="text" name="product_search" id="product-search" class="form-control" placeholder="{{ translate('search product') }}">

                    <div class="product-search-result bg-white shadow-soft rounded mt-1 px-3">
                        @foreach($products as $product)
                            <div class="border-bottom py-3 result">
                                <a class="media gap-3" href="{{ route('admin.flash-sale.add-product-to-session', [$flash_sale_id, $product['id']]) }}">
                                    <img class="selected-product-img rounded border p-1" width="55"
                                         src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'], true)[0]}}"
                                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="img">
                                    <div class="media-body">
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <div class="d-flex flex-wrap column-gap-3 fs-12">
                                            <div>{{ translate('price') }} : {{ Helpers::set_symbol($product->price) }}</div>
                                            <div>{{ translate('total_stock') }} : {{ $product->total_stock }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="selected-products">
                    @php
                        $selected_products = session()->get('selected_products', []);
                    @endphp
                    @foreach($selected_products as $selected_product)
                        @if($selected_product['flash_sale_id'] == $flash_sale_id)
                            <div class="bg-light rounded selected-product-item p-3 position-relative">
                                <a class="remove-item-btn" href="{{ route('admin.flash-sale.delete-product-from-session', [$flash_sale_id, $selected_product['product_id']]) }}">
                                    <i class="tio-clear"></i>
                                </a>
                                <div class="media gap-3">
                                    <img class="selected-product-img rounded border p-1" width="55"
                                         src="{{asset('storage/app/public/product')}}/{{$selected_product['image']}}"
                                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="img">
                                    <div class="media-body">
                                        <h6 class="mb-1">{{ $selected_product['name'] }}</h6>
                                        <div class="d-flex flex-wrap column-gap-3 fs-12">
                                            <div>{{ translate('price') }} : {{ Helpers::set_symbol($selected_product['price']) }}</div>
                                            <div>{{ translate('Current Stock') }} : {{ $selected_product['total_stock'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <div class="d-flex justify-content-end gap-3 mt-3">
                    <a type="reset" class="btn btn-secondary px-5" href="{{ route('admin.flash-sale.delete-all-products-from-session', [$flash_sale_id]) }}">{{translate('reset')}}</a>
                    <a type="submit" class="btn btn-primary px-5" onclick="document.getElementById('product_store').submit()">{{translate('submit')}}</a>
                    <form
                        action="{{route('admin.flash-sale.add_flash_sale_product', [$flash_sale_id])}}"
                        method="post" id="product_store" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="card mt-4">
            <div class="px-20 py-3">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-4">
                        <h5 class="text-capitalize d-flex align-items-center gap-2 mb-0">
                            {{translate('Flash Sale Table')}}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $flash_sale_products->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                            <form  action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control" value="{{ $search }}"
                                        placeholder="{{translate('Search by name')}}" aria-label="Search"
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
                            <th>{{translate('Name')}}</th>
                            <th>{{translate('Price')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($flash_sale_products as $key => $product)
                        <tr>
                            <td> {{$flash_sale_products->firstitem()+$key}}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ Helpers::set_symbol($product->price) }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                       onclick="form_alert('flash-product-delete-{{$product->id}}','{{translate('Want to delete this product ?')}}')"><i class="tio tio-delete"></i></a>
                                </div>
                                <form action="{{route('admin.flash-sale.product.delete',[$flash_sale_id, $product->id])}}"
                                      method="post" id="flash-product-delete-{{$product->id}}">
                                    @csrf
                                    @method('delete')
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
                    {!! $flash_sale_products->links() !!}
                </div>
            </div>
             @if(count($flash_sale_products)==0)
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
        $(document).on('ready', function () {
            $('.product-search-result').on('click', function() {
                $(this).siblings('input').focus();
            })
        })

        $(document).ready(function() {
            // Listen for changes in the search input
            $("#product-search").on("input", function() {
                var searchQuery = $(this).val().toLowerCase();

                // Iterate through each product and hide/show based on the search query
                $(".product-search-result .result").each(function() {
                    var productName = $(this).find("h6").text().toLowerCase();
                    if (productName.includes(searchQuery)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
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
@endpush
