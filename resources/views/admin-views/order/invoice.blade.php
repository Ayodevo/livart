@extends('layouts.admin.app')

@section('title','')

@push('css_or_js')
    <style>
        @media print {
            .non-printable {
                display: none;
            }

            .printable {
                display: block;
                font-family: emoji !important;
            }

            body {
                -webkit-print-color-adjust: exact !important; /* Chrome, Safari */
                color-adjust: exact !important;
                font-family: emoji !important;
            }
        }
    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 2px;  /* this affects the margin in the printer settings */
            font-family: emoji !important;
        }

    </style>
@endpush

@section('content')

    <div class="content container-fluid">
        <div class="row" id="printableArea" style="font-family: emoji;">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                           value="Proceed, If thermal printer is ready."/>
                    <a href="{{url()->previous()}}" class="btn btn-danger non-printable">Back</a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5">
                <div class="text-center pt-4 mb-3">
                    <h2 style="line-height: 1">{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>
                    <h5 style="font-size: 20px;font-weight: lighter;line-height: 1">
                        {{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}
                    </h5>
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        Phone : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
                    </h5>
                </div>

                <span>---------------------------------------------------------------------------------</span>
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>Order ID : {{$order['id']}}</h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            {{date('d/M/Y h:m a',strtotime($order['created_at']))}}
                        </h5>
                    </div>
                    <div class="col-12">
                        <h5>
                            Customer Name : {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                        </h5>
                        <h5>
                            Phone : {{$order->customer['phone']}}
                        </h5>
                        @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                        <h5>
                            Address : {{isset($address)?$address['address']:''}}
                        </h5>
                    </div>
                </div>
                <h5 class="text-uppercase"></h5>
                <span>---------------------------------------------------------------------------------</span>
                <table class="table table-bordered mt-3" style="width: 98%">
                    <thead>
                    <tr>
                        <th style="width: 10%">QTY</th>
                        <th class="">DESC</th>
                        <th class="">Price</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @php($total_dis_on_pro=0)
                    @php($add_ons_cost=0)
                    @foreach($order->details as $detail)
                        @if($detail->product_details != null)
                            @php($product = json_decode($detail->product_details, true))

                            @php($add_on_qtys=json_decode($detail['add_on_qtys'],true))
                            <tr>
                                <td class="">
                                    {{$detail['quantity']}}
                                </td>
                                <td class="">
                                    {{$product['name']}} <br>
                                    @if(count(json_decode($detail['variation'],true))>0)
                                        <strong><u>Variation : </u></strong>
                                        @foreach(json_decode($detail['variation'],true)[0] ?? json_decode($detail['variation'],true) as $key1 =>$variation)
                                            <div class="font-size-sm text-body">
                                                <span>{{$key1}} :  </span>
                                                <span class="font-weight-bold">{{$variation}} {{$key1=='price'?\App\CentralLogics\Helpers::currency_symbol():''}}</span>
                                            </div>
                                        @endforeach
                                    @endif

                                    @foreach(json_decode($detail['add_on_ids'],true) as $key2 =>$id)
                                        @php($addon=\App\Model\AddOn::find($id))
                                        @if($key2==0)<strong><u>Addons : </u></strong>@endif

                                        @if($add_on_qtys==null)
                                            @php($add_on_qty=1)
                                        @else
                                            @php($add_on_qty=$add_on_qtys[$key2])
                                        @endif

                                        <div class="font-size-sm text-body">
                                            <span>{{$addon['name']}} :  </span>
                                            <span class="font-weight-bold">
                                                            {{$add_on_qty}} x {{$addon['price']}} {{\App\CentralLogics\Helpers::currency_symbol()}}
                                                        </span>
                                        </div>
                                        @php($add_ons_cost+=$addon['price']*$add_on_qty)
                                    @endforeach

                                    Discount : {{ Helpers::set_symbol($detail['discount_on_product']) }}
                                </td>
                                <td style="width: 28%">
                                    @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                                    {{ Helpers::set_symbol($amount) }}
                                </td>
                            </tr>
                            @php($sub_total+=$amount)
                            @php($total_tax+=$detail['tax_amount']*$detail['quantity'])
                        @endif
                    @endforeach
                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            <dt class="col-6">Items Price:</dt>
                            <dd class="col-6">{{ Helpers::set_symbol($sub_total) }}</dd>
                            <dt class="col-6">Tax / VAT:</dt>
                            <dd class="col-6">{{ Helpers::set_symbol($total_tax) }}</dd>
                            <dt class="col-6">Addon Cost:</dt>
                            <dd class="col-6">
                                {{Helpers::set_symbol($add_ons_cost) }}
                                <hr>
                            </dd>

                            <dt class="col-6">Subtotal:</dt>
                            <dd class="col-6">
                                {{Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>
                            <dt class="col-6">Coupon Discount:</dt>
                            <dd class="col-6">
                                - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>
                            <dt class="col-6">Delivery Fee:</dt>
                            <dd class="col-6">
                                @if($order['order_type']=='take_away')
                                    @php($del_c=0)
                                @else
                                    @php($del_c=$order['delivery_charge'])
                                @endif
                                {{ Helpers::set_symbol($del_c) }}
                                <hr>
                            </dd>

                            <dt class="col-6" style="font-size: 20px">Total:</dt>
                            <dd class="col-6" style="font-size: 20px">{{ Helpers::set_symbol($sub_total+$del_c+$total_tax+$add_ons_cost-$order['coupon_discount_amount']) }}</dd>
                        </dl>
                    </div>
                </div>
                <span>---------------------------------------------------------------------------------</span>
                <h5 class="text-center pt-3">
                    """THANK YOU"""
                </h5>
                <span>---------------------------------------------------------------------------------</span>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
