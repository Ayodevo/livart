@extends('layouts.admin.app')

@section('title', translate('Customer List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/customer.png')}}" alt="{{ translate('customer') }}">
                {{translate('customers')}}
            </h2>
        </div>

        <div class="card">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <h5 class="d-flex align-items-center gap-2 mb-0">
                    {{translate('customer_List')}}
                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $customers->total() }}</span>
                    <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="Add Customer">
                        <i class="tio-user-add"></i>
                        {{translate('Customer')}}
                    </button>

                </h5>

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
                                <form action="{{route('admin.pos.customer-store')}}" method="post" id="customer-form">
                                    @csrf
                                    <div class="row pl-2">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('First_Name')}}<span class="input-label-secondary text-danger">*</span></label>
                                                <input type="text" name="f_name" class="form-control" value="" placeholder="{{ translate('First name') }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('Last_Name')}}<span class="input-label-secondary text-danger">*</span></label>
                                                <input type="text" name="l_name" class="form-control" value="" placeholder="{{ translate('Last name') }}" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pl-2">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('Email')}}<span class="input-label-secondary text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="" placeholder="{{ translate('Ex : ex@example.com') }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('Phone')}}<span class="input-label-secondary text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control" value="" placeholder="{{ translate('Phone') }}" required="">
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

                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <select id="orderSelect" class="custom-select custom-select-sm min-h-40" name="order">
                            <option value="all">{{ translate('All fields') }}</option>
                            <option value="name">{{ translate('Name') }}</option>
                            <option value="email">{{ translate('Email') }}</option>
                            <option value="phone">{{ translate('Phone') }}</option>
                        </select>
                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{translate('Search')}}" aria-label="Search"  value="{{$search}}" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>


            <script>
                // Get the select element
                const orderSelect = document.getElementById('orderSelect');
                // Get the current selected value from the query string
                const currentOrder = new URLSearchParams(window.location.search).get('order');

                // Set the selected option based on the current value in the query string
                if (currentOrder) {
                    orderSelect.value = currentOrder;
                }

                // Refresh the page if "All fields" is selected
                orderSelect.addEventListener('change', function() {
                    if (this.value === 'all') {
                        window.location.href = 'https://livart.app/backend/admin/customer/list'; // Redirige vers la route spécifiée
                    }
                });

                // Prevent the form from being submitted if the select value is not changed
                document.getElementById('searchForm').addEventListener('submit', function(event) {
                    if (orderSelect.value === currentOrder) {
                        event.preventDefault();
                    }
                });

            </script>

            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('customer_name')}}</th>
                            <th>{{translate('contact_info')}}</th>
                            <th>{{translate('total_Order')}}</th>
                            <th class="text-center">{{translate('actions')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>
                                {{$customers->firstitem()+$key}}
                            </td>
                            <td>
                                <a class="text-dark media gap-3 align-items-center" href="{{route('admin.customer.view',[$customer['id']])}}">
                                    <div class="avatar rounded-circle">
                                        <img class="img-fit rounded-circle" src="{{$customer['image_fullpath']}}" alt="{{ translate('customer') }}">
                                    </div>
                                    <div class="media-body">{{$customer['f_name']." ".$customer['l_name']}}</div>
                                </a>
                            </td>
                            <td>
                                <div><a class="text-dark" href="mailto:{{$customer['email']}}">{{$customer['email']}}</a></div>
                                <div><a class="text-dark" href="tel:{{$customer['phone']}}">{{$customer['phone']}}</a></div>
                            </td>
                            <td>
                                <label class="badge badge-soft-info">
                                    {{$customer->orders->count()}}
                                </label>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info btn-sm square-btn"  data-toggle="modal" data-target="#edit-customer{{$customer->id}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-primary btn-sm square-btn" href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="tio-visible"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm square-btn"
                                       href="{{ route('admin.pos.customer-delete', $customer->id) }}"
                                       onclick="return confirm('Are you sure you want to delete this customer?');">
                                        <i class="tio-add-to-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>


                        <div class="modal fade" id="edit-customer{{$customer->id}}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{translate('update_Customer')}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin.pos.customer-update')}}" method="post" id="customer-form">
                                            @csrf
                                            @method('put')
                                            <div class="row pl-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <input type="hidden" name="user_id" value="{{$customer->id}}">
                                                        <label class="input-label">{{translate('First_Name')}}<span class="input-label-secondary text-danger">*</span></label>
                                                        <input type="text" name="f_name" class="form-control" value="{{$customer->f_name}}" placeholder="{{ translate('First name') }}" required="" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('Last_Name')}}<span class="input-label-secondary text-danger">*</span></label>
                                                        <input type="text" name="l_name" class="form-control"  value="{{$customer->l_name}}" placeholder="{{ translate('Last name') }}" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pl-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('Email')}}<span class="input-label-secondary text-danger">*</span></label>
                                                        <input type="email" name="email" class="form-control"  value="{{$customer->email}}" placeholder="{{ translate('Ex : ex@example.com') }}" required="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('Phone')}}<span class="input-label-secondary text-danger">*</span></label>
                                                        <input type="text" name="phone" class="form-control"  value="{{$customer->phone}}" placeholder="{{ translate('Phone') }}" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" id="" class="btn btn-primary">{{translate('Update')}}</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $customers->links() !!}
                </div>
            </div>
            @if(count($customers)==0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin//svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection


