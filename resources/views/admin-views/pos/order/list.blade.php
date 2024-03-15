@extends('layouts.admin.app')

@section('title', translate('Order List'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img src="{{asset('public/assets/admin/img/icons/all_orders.png')}}" alt="{{ translate('pos_order') }}">
                {{translate('pos_orders')}}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $orders->total() }}</span>
        </div>

        <div class="card">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="#" id="form-data" method="GET">
                        <div class="row align-items-end gy-3 gx-2">
                            <div class="col-12 pb-0">
                                <h4>{{translate('Select_Date_Range')}}</h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label for="filter">{{translate('Select_Branch')}}</label>
                                <select class="custom-select custom-select-sm min-h-40" name="branch_id">
                                    <option disabled>--- {{translate('select')}} {{translate('branch')}} ---</option>
                                    <option value="all" {{ $branchId == 'all' ? 'selected': ''}}>{{translate('all')}} {{translate('branch')}}</option>
                                    @foreach(\App\Model\Branch::all() as $branch)
                                        <option value="{{$branch['id']}}" {{ $branch['id'] == $branchId ? 'selected' : ''}}>{{$branch['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div>
                                    <label for="form_date">{{translate('Start_Date')}}</label>
                                    <input type="date" id="start_date" name="start_date" value="{{$startDate}}" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="yy-mm-dd" data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0">
                                <div>
                                    <label for="to_date">{{translate('End_date')}}</label>
                                    <input type="date" id="end_date" name="end_date" value="{{$endDate}}" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="yy-mm-dd" data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0 __btn-row">
                                <a href="{{ route('admin.pos.orders') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                <button type="submit" class="btn btn-primary btn-block">{{translate('Show_Data')}}</button>
                            </div>
                        </div>
                    </form>

                    <form action="{{ url()->current() }}" id="form-data" method="GET">
                        <div class="row align-items-end gy-3 gx-2">
                            <div class="col-12 pb-0">
                                <h4>{{translate('Select_Price_Range')}}</h4>
                            </div>
                            <div class="col-sm-6 col-md-5">
                                <div>
                                    <label for="form_date">{{translate('Min Price')}}</label>
                                    <input type="number"  name="min" value="{{$startDate}}" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="100" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mt-2 mt-sm-0">
                                <div>
                                    <label for="to_date">{{translate('Max Price')}}</label>
                                    <input type="number"  name="max" value="{{$endDate}}" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="200" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0 __btn-row">
                                <a href="{{ route('admin.pos.orders') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                <button type="submit" class="btn btn-primary btn-block">{{translate('Show_Data')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-3">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form id="searchForm" action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <select id="orderPosSelect" class="custom-select custom-select-sm min-h-40" name="order_pos">
                                    <option value="all">{{ translate('All fields') }}</option>
                                    <option value="id">{{ translate('Order Id') }}</option>
                                    <option value="payment_status">{{ translate('Payment Status') }}</option>
                                </select>

                                <div class="col-sm-6 col-md-3" id="paymentStatusSelect" style="display: none;">
                                    <select class="custom-select custom-select-sm min-h-40" name="payment_status">
                                        <option value="paid">{{ translate('Paid') }}</option>
                                        <option value="unpaid">{{ translate('Unpaid') }}</option>
                                    </select>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control custom-select-sm min-h-40" placeholder="{{ translate('Search') }}" aria-label="Search" value="{{ $search }}"  autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">{{ translate('Search') }}</button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var orderPosSelect = document.getElementById('orderPosSelect');
                                var searchInput = document.getElementById('datatableSearch_');
                                var paymentStatusSelect = document.getElementById('paymentStatusSelect');
                                var selectElements = document.querySelectorAll('select');
                                selectElements.forEach(function(select) {
                                    select.addEventListener('change', function() {
                                        // Vide le champ de recherche
                                        searchInput.value = '';
                                    });
                                });

                                // Écoute l'événement de changement sur le select "État de paiement"
                                orderPosSelect.addEventListener('change', function() {
                                    if (this.value === 'payment_status') {
                                        // Masque le champ de recherche si "Payment Status" est sélectionné
                                        searchInput.style.display = 'none';
                                        paymentStatusSelect.style.display = 'block';
                                    } else {
                                        // Affiche le champ de recherche si une autre option est sélectionnée
                                        searchInput.style.display = 'block';
                                        paymentStatusSelect.style.display = 'none';
                                    }
                                });

                                // Exécute l'événement de changement au chargement de la page pour afficher ou masquer le champ de recherche en fonction de la valeur actuelle
                                if (orderPosSelect.value === 'payment_status') {
                                    searchInput.style.display = 'none';
                                    paymentStatusSelect.style.display = 'block';
                                } else {
                                    searchInput.style.display = 'block';
                                    paymentStatusSelect.style.display = 'none';
                                }
                            });
                        </script>
                        <script>
                            // Get the select element
                            const orderPosSelect = document.getElementById('orderPosSelect');

                            // Get the current selected value from the query string
                            const currentOrderPos = new URLSearchParams(window.location.search).get('order_pos');

                            // Set the selected option based on the current value in the query string
                            if (currentOrderPos) {
                                orderPosSelect.value = currentOrderPos;
                            }

                            // Refresh the page if "All fields" is selected
                            orderPosSelect.addEventListener('change', function() {
                                if (this.value === 'all') {
                                    window.location.href = 'https://livart.app/backend/admin/pos/orders'; // Redirige vers la route spécifiée
                                }
                            });

                        </script>
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="false">
                                <i class="tio-download-to"></i>{{ translate('export') }}<i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right w-auto">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{route('admin.pos.orders.export', ['branch_id'=>Request::get('branch_id'), 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date'), 'search'=>Request::get('search')])}}">
                                        <img width="14" src="{{asset('public/assets/admin/img/icons/excel.png')}}" alt="{{ translate('excel') }}">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-dark">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('order_ID')}}</th>
                        <th>{{translate('Order_date')}}</th>
                        <th>{{translate('customer_info')}}</th>
                        <th>{{translate('branch')}}</th>
                        <th>{{translate('total_amount')}}</th>
                        <th>{{translate('order_status')}}</th>
                        <th>{{translate('order_type')}}</th>
                        <th class="text-center">{{translate('actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)
                        <tr class="status-{{$order['order_status']}} class-all">
                            <td>{{$key+$orders->firstItem()}}</td>
                            <td>
                                <a class="text-dark" href="{{route('admin.pos.order-details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>
                                <div>{{date('d M Y',strtotime($order['created_at']))}}</div>
                                <div class="fs-12">{{date("h:i A",strtotime($order['created_at']))}}</div>
                            </td>
                            <td>
                                @if($order->customer)
                                    <a class="text-dark text-capitalize"  href="{{route('admin.customer.view',[$order['user_id']])}}">
                                        <h6 class="mb-0">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</h6>
                                    </a>
                                    <a class="text-dark fs-12" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                @elseif($order->user_id != null && !isset($order->customer))
                                    <h6 class="text-muted text-capitalize">{{translate('customer_deleted')}}</h6>
                                @else
                                    <h6 class="text-muted text-capitalize">{{translate('walk_in_customer')}}</h6>
                                @endif
                            </td>
                            <td>
                                <label class="badge badge-soft-primary">{{$order->branch?$order->branch->name:'Branch deleted!'}}</label>
                            </td>
                            <td>
                                <div>{{ Helpers::set_symbol($order['order_amount']) }}</div>
                                @if($order->payment_status=='paid')
                                    <span class="text-success">{{translate('paid')}}</span>
                                @else
                                    <span class="text-danger">{{translate('unpaid')}}</span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info">{{translate('pending')}}</span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info">{{translate('confirmed')}}</span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning">{{translate('processing')}}</span>
                                @elseif($order['order_status']=='picked_up')
                                    <span class="badge badge-soft-warning">{{translate('out_for_delivery')}}</span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success">{{translate('delivered')}}</span>
                                @else
                                    <span class="badge badge-soft-danger">{{str_replace('_',' ',$order['order_status'])}}</span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                <span class="badge-soft-success px-2 py-1 rounded">{{translate($order['order_type'])}}</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    @if($order->payment_status != 'paid')
                                        <a class="btn btn-outline-dark square-btn" href="#" data-toggle="modal" data-target="#copyModal{{$order->id}}">
                                            <i class="tio-copy"></i>
                                        </a>
                                    @endif

                                    <!-- <a class="btn btn-outline-primary square-btn"
                                       href="#">
                                        <i class="tio-edit"></i>
                                    </a> !-->

                                    <div class="modal fade" id="copyModal{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Link</h5>

                                                </div>
                                                <div class="modal-body">
                                                    <div id="copyAlert" class="alert alert-success" style="display: none;">Link Copied !</div>
                                                    <input readonly type="text" class="form-control textToCopy" value="http://livart.app/backend/admin/link/order/{{$order->id}}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" onclick="copyInputText(this)" class="btn btn-primary">Copy</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <a class="btn btn-outline-danger square-btn"
                                       href="{{ route('admin.pos.handleDeletePOSOrder', ['id' => $order['id']]) }}"
                                       onclick="return confirm('Are you sure you want to delete this order?');">
                                        <i class="tio-remove-from-trash"></i>
                                    </a>


                                    <a class="btn btn-outline-primary square-btn"
                                       href="{{route('admin.pos.order-details',['id'=>$order['id']])}}">
                                        <i class="tio-visible"></i>
                                    </a>

                                    <button class="btn btn-outline-info square-btn invoice-printing" target="_blank" type="button"
                                            data-id="{{$order->id}}">
                                        <i class="tio-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $orders->links() !!}
                </div>
            </div>
            @if(count($orders)==0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('print_invoice')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 justify-content-center">
                        <input type="button" class="btn btn-primary non-printable print-div-button"
                               data-name="printableArea"
                               value="Proceed, If thermal printer is ready."/>
                        <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                    </div>
                    <hr class="non-printable">
                    <div class="row" id="printableArea">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict"

        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('.invoice-printing').on('click', function (){
            let orderId = $(this).data('id');
            print_invoice(orderId);
        })

        function print_invoice(order_id) {
            $.get({
                url: '{{url('/')}}/admin/pos/invoice/'+order_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $('.print-div-button').on('click', function (){
            let name = $(this).data('name');
            printDiv(name);
        })

        function printDiv(divName) {
            let printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            location.reload();
        }
    </script>
    <script>
        function copyInputText(button) {
            var row = button.closest(".modal-content");
            var textToCopy = row.querySelector(".textToCopy");

            // Sélectionne le texte dans l'élément input
            textToCopy.select();
            // Copie le texte sélectionné dans le presse-papiers
            document.execCommand("copy");
            // Désélectionne le texte après la copie
            textToCopy.setSelectionRange(0, 0);

            // Affiche l'alerte pour indiquer que le texte a été copié
            var copyAlert = row.querySelector("#copyAlert");
            copyAlert.style.display = "block";
            setTimeout(function(){
                copyAlert.style.display = "none"; // Masque l'alerte après quelques secondes
            }, 2000); // 2000 millisecondes (2 secondes)
        }
    </script>

@endpush
