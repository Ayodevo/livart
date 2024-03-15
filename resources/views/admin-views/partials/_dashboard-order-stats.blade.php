<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats"  href="{{route('admin.customer.list')}}">
        <div class="order-stats__content">
           <i class="fa fa-users"></i>
            <h6 class="order-stats__subtitle">{{translate('All_users')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['users']}}</span>
    </a>
</div>




<div class="col-sm-6 col-lg-6" style="height: 90px;">
    <a class="order-stats"href="{{route('admin.orders.list',['all'])}}">
        <div class="order-stats__content">
            <i class="fa fa-list"></i>
            <h6 class="order-stats__subtitle">{{translate('All_orders')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['all_orders']}}</span>
    </a>
</div>


<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats"href="{{route('admin.product.list')}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/failed_to_deliver.png')}}" class="order-stats__img" alt="{{ translate('failed to deliver') }}">
            <h6 class="order-stats__subtitle">{{translate('Low_Stock')}}</h6>
        </div>
        <span class="order-stats__title text-danger">{{$data['low_stock']}}</span>
    </a>
</div>













<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats"  href="{{route('admin.orders.list',['pending'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/pending.png')}}" class="order-stats__img"  alt="{{ translate('pending') }}">
            <h6 class="order-stats__subtitle">{{translate('pending')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['pending']}}</span>
    </a>
</div>


<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats"href="{{route('admin.orders.list',['confirmed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/confirmed.png')}}" class="order-stats__img"  alt="{{ translate('confirmed') }}">
            <h6 class="order-stats__subtitle">{{translate('confirmed')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['confirmed']}}</span>
    </a>
</div>





<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats" href="{{route('admin.orders.list',['processing'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/packaging.png')}}" class="order-stats__img" alt="{{ translate('packaging') }}">
            <h6 class="order-stats__subtitle">{{translate('packaging')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['processing']}}</span>
    </a>
</div>



<div class="col-sm-6 col-lg-3" style="height: 90px;">
    <a class="order-stats" href="{{route('admin.orders.list',['out_for_delivery'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/out_for_delivery.png')}}" class="order-stats__img" alt="{{ translate('Out For delivery') }}">
            <h6 class="order-stats__subtitle">{{translate('out_for_delivery')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['out_for_delivery']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['delivered'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/delivered.png')}}" class="order-stats__img" alt="{{ translate('delivered') }}">
            <h6 class="order-stats__subtitle">{{ translate('delivered') }}</h6>
        </div>
        <span class="order-stats__title">{{$data['delivered']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['canceled'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/cancel.png')}}" class="order-stats__img" alt="{{ translate('delivered') }}">
            <h6 class="order-stats__subtitle">{{ translate('canceled') }}</h6>
        </div>
        <span class="order-stats__title">{{$data['canceled']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['returned'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/returned.png')}}" class="order-stats__img" alt="{{ translate('returned') }}">
            <h6 class="order-stats__subtitle">{{ translate('returned') }}</h6>
        </div>
        <span class="order-stats__title text-danger">{{$data['returned']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['failed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/failed_to_deliver.png')}}" class="order-stats__img" alt="{{ translate('failed to deliver') }}">
            <h6 class="order-stats__subtitle">{{translate('failed_to_delivered')}}</h6>
        </div>
        <span class="order-stats__title text-danger">{{$data['failed']}}</span>
    </a>
</div>
