@extends('layouts.branch.app')

@section('title', translate('POS'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="row gy-3 gx-2">
            <div class="col-lg-7">
                <div class="card overflow-hidden">
                    <!-- POS Title -->
                    <div class="pos-title">
                        <h4 class="mb-0">{{translate('Product_Section')}}</h4>
                    </div>
                    <!-- End POS Title -->

                    {{-- POS Filter --}}
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between gap-3 gap-xl-4 px-4 py-4">
                        <div class="w-100 mr-xl-2">
                            <select name="category" id="category" class="form-control js-select2-custom mx-1" title="select category" onchange="set_category_filter(this.value)">
                                <option value="">{{translate('All Categories')}}</option>
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100 mr-xl-2">
                            <form id="search-form" class="header-item">
                                <div class="input-group input-group-merge input-group-flush border rounded">
                                    <div class="input-group-prepend pl-2">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search"
                                        class="form-control border-0 pr-2"
                                        placeholder="{{\App\CentralLogics\translate('Search here')}}"
                                        aria-label="Search here">
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- POS Filter --}}

                    <div class="card-body pt-0" id="items">
                        <div class="pos-item-wrap justify-content-center">
                            @foreach($products as $product)
                                @include('branch-views.pos._single_product',['product'=>$product])
                            @endforeach
                        </div>
                    </div>
                    <div class="px-3 d-flex justify-content-end">
                        {!!$products->withQueryString()->links()!!}
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card billing-section-wrap">
                    <!-- POS Title -->
                    <div class="pos-title">
                        <h4 class="mb-0">{{translate('Billing_Section')}}</h4>
                    </div>
                    <!-- End POS Title -->

                    <div class="p-2 p-sm-4">
                        <div class="form-group d-flex flex-wrap flex-sm-nowrap gap-2">
                            <select onchange="store_key('customer_id',this.value)" id='customer' name="customer_id"
                                    data-placeholder="{{translate('Walk In Customer')}}"
                                    class="js-data-example-ajax form-control">
                                <option disabled selected>{{translate('select Customer')}}</option>
                                @foreach(\App\User::select('id', 'f_name', 'l_name')->get() as $customer)
                                    <option value="{{$customer['id']}}" {{ session()->get('customer_id') == $customer['id'] ? 'selected' : '' }}>{{$customer['f_name']. ' '. $customer['l_name'] }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="Add Customer">
                                <i class="tio-add"></i>
                                {{translate('Customer')}}
                            </button>
                        </div>

                        <div id="cart">
                            @include('branch-views.pos._cart')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>


    <!-- ADD Customer Modal -->
    <div class="modal fade" id="add-customer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_Customer')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('branch.pos.customer-store')}}" method="post" id="customer-form">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">
                                        {{translate('First_Name')}}
                                        <span class="input-label-secondary text-danger">*</span>
                                    </label>
                                    <input type="text" name="f_name" class="form-control" value="" placeholder="First name" required="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">
                                        {{translate('Last_Name')}}
                                        <span class="input-label-secondary text-danger">*</span>
                                    </label>
                                    <input type="text" name="l_name" class="form-control" value="" placeholder="Last name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">
                                        {{translate('Email')}}
                                        <span class="input-label-secondary text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control" value="" placeholder="Ex : ex@example.com" required="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">
                                        {{translate('Phone')}}
                                        <span class="input-label-secondary text-danger">*</span>
                                    </label>
                                    <input type="text" name="phone" class="form-control" value="" placeholder="Phone" required="">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @php($order=\App\Model\Order::find(session('last_order')))
    @if($order)
    @php(session(['last_order'=> false]))
    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Print Invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row" style="font-family: emoji;">
                    <div class="col-md-12">
                        <center>
                            <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                                value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                            <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                        </center>
                        <hr class="non-printable">
                    </div>
                    <div class="row" id="printableArea" style="margin: auto;">
                        @include('branch-views.pos.order.invoice')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('script_2')

    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function () {
            @if($order)
            $('#print-invoice').modal('show');
            @endif
        });
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function set_category_filter(id) {
            var nurl = new URL('{!!url()->full()!!}');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }


        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var keyword= $('#datatableSearch').val();
            var nurl = new URL('{!!url()->full()!!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        // function addon_quantity_input_toggle(e)
        // {
        //     var cb = $(e.target);
        //     if(cb.is(":checked"))
        //     {
        //         cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        //     }
        //     else
        //     {
        //         cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        //     }
        // }
        function quickView(product_id) {
            $.ajax({
                url: '{{route('branch.pos.quick-view')}}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json', // added data type
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log("success...");
                    console.log(data);

                    // $("#quick-view").removeClass('fade');
                    // $("#quick-view").addClass('show');

                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function checkAddToCartValidity() {
            return true;
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function (e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {
                        if (currentVal <  parseInt(input.attr('max')) ) {
                            input.val(currentVal + 1).change();
                        }
                        if (currentVal >=  parseInt(input.attr('max')) ) {
                            console.log(input.val(currentVal))
                            Swal.fire({
                                icon: 'error',
                                title: '{{translate('Cart')}}',
                                confirmButtonText:'{{translate("Ok")}}',
                                text: '{{\App\CentralLogics\translate('stock limit exceeded')}}.'
                            });
                            input.val(currentVal).change();
                        }
                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function () {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function () {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{translate('Cart')}}',
                        confirmButtonText:'{{translate("Ok")}}',
                        text: '{{\App\CentralLogics\translate('Sorry, the minimum value was reached')}}',
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{translate('Cart')}}',
                        confirmButtonText:'{{translate("Ok")}}',
                        text: '{{\App\CentralLogics\translate('Sorry, stock limit exceeded')}}.'
                    });
                    $(this).val($(this).data('oldValue'));
                }
            });
            $(".input-number").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('branch.pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function (data) {
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        $('#add-to-cart-form #quantity_div #quantity').attr({"max" : data.stock});
                    }
                });
            }
        }

        function addToCart(form_id = 'add-to-cart-form') {
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('branch.pos.add-to-cart') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        console.log(data)
                        if (data.data == 1) {
                            Swal.fire({
                                icon: 'info',
                                title: '{{translate('Cart')}}',
                                confirmButtonText:'{{translate("Ok")}}',
                                text: "{{\App\CentralLogics\translate('Product already added in cart')}}"
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{translate('Cart')}}',
                                confirmButtonText:'{{translate("Ok")}}',
                                text: '{{\App\CentralLogics\translate('Sorry, product out of stock')}}.'
                            });
                            return false;
                        }
                        $('.call-when-done').click();

                        toastr.success('{{\App\CentralLogics\translate('Item has been added in your cart')}}!', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                        updateCart();
                    },
                    complete: function () {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: '{{translate('Cart')}}',
                    confirmButtonText:'{{translate("Ok")}}',
                    text: '{{\App\CentralLogics\translate('Please choose all the options')}}'
                });
            }
        }

        function removeFromCart(key) {
            $.post('{{ route('branch.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    updateCart();
                    toastr.info('{{\App\CentralLogics\translate('Item has been removed from cart')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }

            });
        }

        function emptyCart() {
            $.post('{{ route('branch.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
                updateCart();
                toastr.info('{{\App\CentralLogics\translate('Item has been removed from cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                location.reload();
            });
        }

        function updateCart() {
            $.post('<?php echo e(route('branch.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
                $('#cart').empty().html(data);
            });
        }

    $(function(){
            $(document).on('click','input[type=number]',function(){ this.select(); });
        });


        function updateQuantity(e){
            var element = $( e.target );
            var minValue = parseInt(element.attr('min'));
            // maxValue = parseInt(element.attr('max'));
            var valueCurrent = parseInt(element.val());

            var key = element.data('key');
            if (valueCurrent >= minValue) {
                $.post('{{ route('branch.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:valueCurrent}, function (data) {
                    updateCart();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate('Cart')}}',
                    confirmButtonText:'{{translate("Ok")}}',
                    text: '{{\App\CentralLogics\translate('Sorry, the minimum value was reached')}}'
                });
                element.val(element.data('oldValue'));
            }


            // Allow: backspace, delete, tab, escape, enter and .
            if(e.type == 'keydown')
            {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }

        };

        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{route('branch.pos.customers')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#order_place').submit(function(eventObj) {
            if($('#customer').val())
            {
                $(this).append('<input type="hidden" name="user_id" value="'+$('#customer').val()+'" /> ');
            }
            return true;
        });

        function store_key(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            $.post({
                url: '{{route('branch.pos.store-keys')}}',
                data: {
                    key:key,
                    value:value,
                },
                success: function (data) {
                    var selected_field_text = key;
                    var selected_field = selected_field_text.replace("_", " ");
                    var selected_field = selected_field.replace("id", " ");
                    var message = selected_field+' '+'selected!';
                    var new_message = message.charAt(0).toUpperCase() + message.slice(1);
                    toastr.success((new_message), {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
            });
        }

    </script>
    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
@endpush
