<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Invoice') }}</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
</head>

<body class="footer-offset">

<main id="content" role="main" class="main pointer-event">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-12 text-center mb-3">
                <img width="150"
                     src="{{asset('storage/app/public/ecommerce')}}/{{\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value}}">
                <h3 class="mb-5 mt-2">{{ translate('Invoice') }} : #{{$order['id']}}</h3>
            </div>
            <div class="col-6 text-dark">
                @if($order->customer)
                <h3>{{ translate('Customer Info') }}</h3>

                    <div class="">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</div>
                    <div class="">{{$order->customer['email']}}</div>
                    <div class="">{{$order->customer['phone']}}</div>
                    <div class="">{{$order->delivery_address?$order->delivery_address['address']:''}}</div><br>
                @endif
            </div>

            <div class="col-6 text-dark text-right">
                <h3>{{ translate('Billing Address') }}</h3>
                <div>{{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}</div>
                <div>{{\App\Model\BusinessSetting::where(['key'=>'email_address'])->first()->value}}</div>
                <div>{{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @php($item_amount=0)
                @php($sub_total=0)
                @php($total_tax=0)
                @php($total_dis_on_pro=0)
                @php($total_item_discount=0)

                <div class="table-responsive">
                    <table class="table table-bordered table-align-middle text-dark">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Item Description') }}</th>
                            <th>{{ translate('Unit Price') }}</th>
                            <th>{{ translate('Discount') }}</th>
                            <th>{{ translate('Qty') }}</th>
                            <th class="text-right">{{ translate('Total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->details as $detail)
                            @if($detail->product_details != null)
                                @php($product = json_decode($detail->product_details, true))

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="media gap-3 max-content">
                                            <div class="avatar-xl">
                                                @if($detail->product && $detail->product['image'] != null )
                                                    <img class="img-fit"
                                                         src="{{asset('storage/app/public/product')}}/{{json_decode($detail->product['image'],true)[0]}}"
                                                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                                         alt="Image Description">
                                                @else
                                                    <img src="{{asset('public/assets/admin/img/160x160/img2.jpg')}}"
                                                         class="img-fit img-fluid rounded aspect-ratio-1">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-1 w-24ch">{{$product['name']}}</h6>
                                                @if(count(json_decode($detail['variation'],true))>0)
                                                    <h6 class="underline mb-0">{{ translate('variation') }}:</h6>
                                                    @foreach(json_decode($detail['variation'],true)[0] ?? json_decode($detail['variation'],true) as $key1 =>$variation)
                                                        <div class="fs-12">{{$key1}}: {{$variation}}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>@if($detail['discount_on_product']!=0)
                                            <h5>
                                                <strike>
{{--                                                    {{\App\CentralLogics\Helpers::variation_price(json_decode($detail['product_details'],true),$detail['variation']) ." ".\App\CentralLogics\Helpers::currency_symbol()}}--}}
                                                </strike>
                                            </h5>
                                        @endif
                                        {{ Helpers::set_symbol($detail['price']) }}
                                    </td>
                                    <td>{{Helpers::set_symbol($detail['discount_on_product'])}}</td>
                                    <td>{{$detail['quantity']}}</td>
                                    <td class="text-right">
                                        @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                                        {{ Helpers::set_symbol($amount) }}
                                    </td>
                                </tr>
                                @php($item_amount+=$detail['price']*$detail['quantity'])
                                @php($sub_total+=$amount)
                                @php($total_tax+=$detail['tax_amount']*$detail['quantity'])
                                @php($total_item_discount += $detail['discount_on_product'] * $detail['quantity'])
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-md-end">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-6">{{ translate('Items Price') }}:</dt>
                            <dd class="col-sm-6 text-right">{{ Helpers::set_symbol($item_amount) }}</dd>

                            <dt class="col-sm-6">{{ translate('item_discount') }}:</dt>
                            <dd class="col-sm-6 text-right">{{ Helpers::set_symbol($total_item_discount) }}</dd>

                            <dt class="col-sm-6">{{ translate('Tax / VAT') }}:</dt>
                            <dd class="col-sm-6 text-right">{{ Helpers::set_symbol($total_tax) }}</dd>

                            <dt class="col-sm-6">{{ translate('Subtotal') }}:</dt>
                            <dd class="col-sm-6 text-right">
                                {{ Helpers::set_symbol($sub_total+$total_tax) }}</dd>

                            <dt class="col-sm-6">{{ translate('Coupon Discount') }}:</dt>
                            <dd class="col-sm-6 text-right">
                                - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>

                            <dt class="col-6">{{\App\CentralLogics\translate('Extra Discount')}}:</dt>
                            <dd class="col-6 text-right">
                                - {{ Helpers::set_symbol($order['extra_discount']) }}</dd>

                            <dt class="col-sm-6">{{ translate('Delivery Fee') }}:</dt>
                            <dd class="col-sm-6 text-right">
                                @if($order['order_type']=='self_pickup')
                                    @php($del_c=0)
                                @else
                                    @php($del_c=$order['delivery_charge'])
                                @endif
                                {{ Helpers::set_symbol($del_c) }}
                            </dd>

                            <dt class="col-sm-6 border-top font-weight-bold pt-2">{{ translate('Total') }}:</dt>
                            <dd class="col-sm-6 border-top font-weight-bold text-right pt-2">{{ Helpers::set_symbol($sub_total+$del_c+$total_tax-$order['coupon_discount_amount']-$order['extra_discount']) }}</dd>
                        </dl>
                        <!-- End Row -->
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>

        <div class="text-dark mt-10">
            <div class="text-center mb-3">If you require any assistance or have feedback or suggestions about our site you
                Can <br /> email us at <a href="#" class="text-primary">{{\App\Model\BusinessSetting::where(['key'=>'email_address'])->first()?->value}}</a>
            </div>

            <div class="invoice-footer-bg py-5 px-4">
                <div class="text-center">
                    <div>{{ translate('phone') }}: {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()?->value}}</div>
                    <div>{{ translate('eamil') }}: {{\App\Model\BusinessSetting::where(['key'=>'email_address'])->first()?->value}}</div>
                    <div><?php echo url('/') ?></div>
                    <div>
                        &copy; {{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()?->value}}.
                        {{\App\Model\BusinessSetting::where(['key'=>'footer_text'])->first()?->value}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{asset('public/assets/admin')}}/js/demo.js"></script>
<!-- JS Implementing Plugins -->
<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script>
    window.print();
</script>
</body>
</html>
