<div class="pos-product-item card" onclick="quickView('{{$product->id}}')">
    <div class="pos-product-item_thumb">
        <img src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'], true)[0]}}"
                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" class="img-fit">
    </div>

    <div class="pos-product-item_content clickable">
        <div class="pos-product-item_title">
            {{ Str::limit($product['name'], 15) }}
        </div>
        <div class="pos-product-item_price">
            {{ Helpers::set_symbol($product['price']- \App\CentralLogics\Helpers::discount_calculate($product, $product['price'])) }}
            {{-- @if($product->discount > 0)
                <strike class="fs-12 text-muted">
                    {{ Helpers::set_symbol($product['price']) }}
                </strike>
            @endif --}}
        </div>
    </div>
</div>
