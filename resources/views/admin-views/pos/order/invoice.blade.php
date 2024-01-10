<div style="width:410px;" class="mx-auto">
    <div class="text-center pt-2 mb-3">
        <h2>{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>
        <h4>{{$order->branch->name}}</h4>
        <h5>
            {{\App\CentralLogics\translate('Branch Address')}} : {{ \App\Model\Branch::find($order['branch_id'])?->address }}
        </h5>
        <p class="text-dark">
            {{\App\CentralLogics\translate('Phone')}}
            : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
        </p>
    </div>

    <div class="invoice-border"></div>
    <div class="row pt-3 pb-2">
        <div class="col-6">
            <h5>{{\App\CentralLogics\translate('Order ID')}} : {{$order['id']}}</h5>
        </div>
        <div class="col-6">
            <div class="text-right text-dark">
                {{date('d M Y h:i a',strtotime($order['created_at']))}}
            </div>
        </div>
        @if($order->customer)
            <div class="col-12 text-dark pb-2">
                <div>{{\App\CentralLogics\translate('Customer Name')}} : {{$order->customer['f_name'].' '.$order->customer['l_name']}}</div>
                <div>{{\App\CentralLogics\translate('Phone')}} : {{$order->customer['phone']}}</div>
                @if($order->order_type != 'pos')
                    <div>
                        {{\App\CentralLogics\translate('Address')}}
                        : {{isset($order->delivery_address)?json_decode($order->delivery_address, true)['address']:''}}
                    </div>
                @endif
            </div>
        @endif
    </div>
    <div class="invoice-border"></div>
    <table class="table table-bordered mt-3 text-dark">
        <thead>
            <tr>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Qty')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Desc')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Price')}}</th>
            </tr>
        </thead>

        <tbody>
        @php($sub_total=0)
        @php($total_tax=0)
        @php($total_dis_on_pro=0)
        @foreach($order->details as $detail)
            @if($detail->product_details != null)
                @php($product = json_decode($detail->product_details, true))

                <tr>
                    <td class="">
                        {{$detail['quantity']}}
                    </td>
                    <td class="">
                        <div class="mb-1"> {{ Str::limit($product['name'], 200) }}</div>
                        @if(count(json_decode($detail['variation'],true))>0)
                            <strong><u>{{\App\CentralLogics\translate('Variation')}} : </u></strong>
                            @foreach(json_decode($detail['variation'],true)[0] ?? json_decode($detail['variation'],true) as $key1 =>$variation)
                                <div class="font-size-sm">
                                    <span>{{$key1}} :  </span>
                                    <strong>
                                        {{$variation}} {{$key1=='price'?\App\CentralLogics\Helpers::currency_symbol():''}}
                                    </strong>
                                </div>
                            @endforeach
                        @endif

                        <div>
                            {{\App\CentralLogics\translate('Discount')}} :
                            {{ Helpers::set_symbol($detail['discount_on_product']*$detail['quantity']) }}
                        </div>
                    </td>
                    <td>
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
    <div class="invoice-border"></div>
    <dl class="row text-dark mt-2">
        <dt class="col-6">{{\App\CentralLogics\translate('Items Price')}}:</dt>
        <dd class="col-6 text-right">{{ Helpers::set_symbol($sub_total) }}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Tax')}} / {{\App\CentralLogics\translate('VAT')}}:</dt>
        <dd class="col-6 text-right">{{Helpers::set_symbol($total_tax) }}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Subtotal')}}:</dt>
        <dd class="col-6 text-right">{{ Helpers::set_symbol($order->order_amount + $order['extra_discount']) }}</dd>

{{--        <dt class="col-6">{{\App\CentralLogics\translate('Coupon Discount')}}:</dt>--}}
{{--        <dd class="col-6 text-right">--}}
{{--            - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}--}}
{{--        </dd>--}}

        <dt class="col-6">{{\App\CentralLogics\translate('Extra Discount')}}:</dt>
        <dd class="col-6 text-right">
            - {{ Helpers::set_symbol($order['extra_discount']) }}
        </dd>

{{--        <dt class="col-6">{{\App\CentralLogics\translate('Delivery Fee')}}:</dt>--}}
{{--        <dd class="col-6 text-right">--}}
{{--            @if($order['order_type']=='take_away')--}}
{{--                @php($del_c=0)--}}
{{--            @else--}}
{{--                @php($del_c=$order['delivery_charge'])--}}
{{--            @endif--}}
{{--            {{ Helpers::set_symbol($del_c) }}--}}
{{--        </dd>--}}
        <dt class="col-6 font-weight-bold">{{\App\CentralLogics\translate('Total')}}:</dt>
        <dd class="col-6 text-right font-weight-bold">{{ Helpers::set_symbol($order->order_amount) }}</dd>
    </dl>
    <div class="invoice-border mt-5"></div>
    <h5 class="text-center mb-0 py-3">
        """{{\App\CentralLogics\translate('THANK YOU')}}"""
    </h5>
    <div class="invoice-border"></div>
</div>
