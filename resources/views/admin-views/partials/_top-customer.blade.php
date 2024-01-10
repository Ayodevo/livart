<!-- Header -->
<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{asset('public/assets/admin/img/icons/top-customers.png')}}" alt="">
        {{\App\CentralLogics\translate('top_customers')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="grid-customers">
        @foreach($top_customer as $key=>$item)
            @if(isset($item->customer))
                <a href="{{route('admin.customer.view', [$item['user_id']])}}" class="shadow d-flex flex-column align-items-center gap-3 rounded py-3">
                    <div class="avatar-lg border rounded-circle">
                        <img class="rounded-circle img-fit"
                        onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                        src="{{asset('storage/app/public/profile/'.$item->customer->image??'')}}"
                        src="storage/app/public/profile/">
                    </div>
                    <div class="text-dark">{{$item->customer['f_name']??'Not exist'}}</div>
                    <div class="px-2 py-1 bg-primary text-white rounded lh-1.3">{{translate("Orders")}} : {{$item['count']}}</div>
                </a>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
