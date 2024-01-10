<!-- Header -->
<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{asset('public/assets/admin/img/icons/top-rated.png')}}" alt="">
        {{\App\CentralLogics\translate('most_rated_products')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body d-flex flex-column gap-3">
    @foreach($most_rated_products as $key=>$item)
        @php($product=\App\Model\Product::find($item['product_id']))
        @if(isset($product))
            <a href="{{route('admin.product.view',[$item['product_id']])}}" class="text-dark d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="media gap-3 align-items-center w-50">
                    <div class="avatar-lg border rounded">
                        <img class="img-fit rounded"
                            src="{{asset('storage/app/public/product')}}/{{ json_decode($product['image'])[0] }}"
                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                            alt="{{$product->name}} image">
                    </div>
                    <span class="media-body">
                        {{isset($product)?substr($product->name,0,30) . (strlen($product->name)>20?'...':''):'not exists'}}
                    </span>
                </div>
                <div class="fs-18 d-flex align-items-center gap-2">
                    {{round($item['ratings_average'],2)}}
                    <i class="tio-star gold"></i>
                </div>
                <div class="fs-18">
                    {{$item['total']}} <i class="tio-user"></i>
                </div>
            </a>
        @endif
    @endforeach
</div>
<!-- End Body -->
