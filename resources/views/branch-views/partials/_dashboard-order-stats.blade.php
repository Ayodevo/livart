
<div class="col-sm-6 col-lg-3">
    <a class="dashboard--card" href="{{route('admin.orders.list',['pending'])}}">
        <h5 class="dashboard--card__subtitle">{{\App\CentralLogics\translate('pending')}}</h5>
        <h2 class="dashboard--card__title">{{$data['pending']}}</h2>
        <img width="30" src="{{asset('public/assets/admin/img/icons/pending.png')}}" class="dashboard--card__img" alt="pending">
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="dashboard--card" href="{{route('admin.orders.list',['confirmed'])}}">
        <h5 class="dashboard--card__subtitle">{{\App\CentralLogics\translate('confirmed')}}</h5>
        <h2 class="dashboard--card__title">{{$data['confirmed']}}</h2>
        <img width="30" src="{{asset('public/assets/admin/img/icons/confirmed.png')}}" class="dashboard--card__img" alt="pending">
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="dashboard--card" href="{{route('admin.orders.list',['processing'])}}">
        <h5 class="dashboard--card__subtitle">{{\App\CentralLogics\translate('packaging')}}</h5>
        <h2 class="dashboard--card__title">{{$data['processing']}}</h2>
        <img width="30" src="{{asset('public/assets/admin/img/icons/packaging.png')}}" class="dashboard--card__img" alt="packaging">
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3">
    <a class="dashboard--card" href="{{route('admin.orders.list',['out_for_delivery'])}}">
        <h5 class="dashboard--card__subtitle">{{\App\CentralLogics\translate('out_for_delivery')}}</h5>
        <h2 class="dashboard--card__title">{{$data['out_for_delivery']}}</h2>
        <img width="30" src="{{asset('public/assets/admin/img/icons/out_for_delivery.png')}}" class="dashboard--card__img" alt="Out For delivery">
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['delivered'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/delivered.png')}}" class="order-stats__img" alt="delivered">
            <h6 class="order-stats__subtitle">{{__('messages.delivered')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['delivered']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['canceled'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/cancel.png')}}" class="order-stats__img" alt="delivered">
            <h6 class="order-stats__subtitle">{{__('messages.canceled')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['canceled']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['returned'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/returned.png')}}" class="order-stats__img" alt="returned">
            <h6 class="order-stats__subtitle">{{__('messages.returned')}}</h6>
        </div>
        <span class="order-stats__title text-danger">{{$data['returned']}}</span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats" href="{{route('admin.orders.list',['failed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('public/assets/admin/img/icons/failed_to_deliver.png')}}" class="order-stats__img" alt="failed to deliver">
            <h6 class="order-stats__subtitle">{{\App\CentralLogics\translate('failed_to_delivered')}}</h6>
        </div>
        <span class="order-stats__title text-danger">{{$data['failed']}}</span>
    </a>
</div>
