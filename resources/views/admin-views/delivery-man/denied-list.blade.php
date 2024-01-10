@extends('layouts.admin.app')

@section('title', translate('New Joining Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/deliveryman.png')}}" alt="">
                {{\App\CentralLogics\translate('New Joining Request')}}
                <span class="badge badge-soft-dark rounded-50 fs-14">{{ $delivery_men->total() }}</span>
            </h2>
            <ul class="nav nav-tabs border-0 mt-2">
                <li class="nav-item">
                    <a class="nav-link {{Request::is('admin/delivery-man/pending/list')?'active':''}}"  href="{{ route('admin.delivery-man.pending') }}">{{ translate('Pending Delivery Man') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{Request::is('admin/delivery-man/denied/list')?'active':''}}"  href="{{ route('admin.delivery-man.denied') }}">{{ translate('Denied Delivery Man') }}</a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="search"
                            class="form-control"
                            placeholder="{{translate('Search by Name')}}" aria-label="Search"
                            value="{{ $search }}" required autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-dark">
                    <thead class="thead-light">
                        <tr>
                            <th>{{\App\CentralLogics\translate('SL')}}</th>
                            <th>{{\App\CentralLogics\translate('name')}}</th>
                            <th>{{\App\CentralLogics\translate('contact_Info')}}</th>
                            <th>{{\App\CentralLogics\translate('branch')}}</th>
                            <th>{{\App\CentralLogics\translate('Identity Type')}}</th>
                            <th>{{\App\CentralLogics\translate('Identity Number')}}</th>
                            <th class="text-center">{{\App\CentralLogics\translate('Identity Image')}}</th>
                            <th class="text-center">{{\App\CentralLogics\translate('action')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($delivery_men as $key=>$dm)
                        <tr>
                            <td>{{$delivery_men->firstitem()+$key}}</td>
                            <td>
                                <div class="media gap-3 align-items-center">
                                    <div class="avatar rounded-circle">
                                        <img class="img-fit rounded-circle"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                            src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}">
                                    </div>
                                    <div class="media-body">{{$dm['f_name'].' '.$dm['l_name']}}</div>
                                </div>
                            </td>
                            <td>
                                <div><a class="text-dark" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a></div>
                                <div><a class="text-dark" href="mailto:{{$dm['email']}}">{{$dm['email']}}</a></div>
                            </td>
                            <td>
                                @if($dm->branch_id == 0)
                                    <label class="badge badge-soft-primary">{{translate('All Branch')}}</label>
                                @else
                                    <label class="badge badge-soft-primary">{{$dm->branch?$dm->branch->name:'Branch deleted!'}}</label>
                                @endif
                            </td>
                            <td>{{ translate($dm->identity_type) }}</td>
                            <td>{{ $dm->identity_number }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2" data-toggle="" data-placement="top" title="{{translate('click for bigger view')}}">
                                    @foreach(json_decode($dm['identity_image'], true) as $identification_image)
                                        @php($image_full_path = asset('storage/app/public/delivery-man'). '/' .$identification_image)
                                        <div class="mx-h80 overflow-hidden">
                                            <img class="cursor-pointer rounded img-fit p-2 w-100px max-h80px"
                                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                                 src="{{$image_full_path}}"
                                                 onclick="show_modal('{{$image_full_path}}')">
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                       data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}"
                                       onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'approved']) }}','{{ translate('you_want_to_approve_this_application') }}')"
                                       href="javascript:"><i class="tio-done font-weight-bold"></i></a>
                                    @if ($dm->application_status != 'denied')
                                        <a class="btn btn-sm btn--danger btn-outline-danger action-btn" data-toggle="tooltip" data-placement="top" title="{{translate('Deny')}}"
                                           onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'denied']) }}','{{ translate('you_want_to_deny_this_application') }}')"
                                           href="javascript:"><i
                                                class="tio-clear"></i></a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $delivery_men->links() !!}
                </div>
            </div>
            @if(count($delivery_men)==0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif

            <div class="modal fade bd-example-modal-lg" id="identification_image_view_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div data-dismiss="modal">
                                <img onerror='this.src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}"' alt=""
                                     class="" id="identification_image_element" style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('script_2')

    <script>
        function show_modal(image_location) {
            $('#identification_image_view_modal').modal('show');
            if(image_location != null || image_location !== '') {
                $('#identification_image_element').attr("src", image_location);
            }
        }
    </script>

    <script>
        function request_alert(url, message) {
            Swal.fire({
                title: '{{ translate('are_you_sure') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.delivery-man.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
