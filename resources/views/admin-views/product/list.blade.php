@extends('layouts.admin.app')

@section('title', translate('Product List'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/product.png')}}" alt="{{ translate('product') }}">
                {{translate('product_list')}}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{$products->total()}}</span>
        </div>
      
          
            

        <div class="row">
            <div class="col-12">
                <div class="card">
                      <form action="{{url()->current()}}" method="GET">
                         <div class="px-20 py-3">
                    
                        <div class="row align-items-end gy-3 gx-2">
                            <div class="col-12 pb-0">
                                <h4>{{translate('Select_Price_Range')}}</h4>
                            </div>
                            <div class="col-sm-6 col-md-5">
    <div>
        <label for="form_date">{{ translate('Min Price') }}</label>
        <input type="number" name="min" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="100">
    </div>
</div>
<div class="col-sm-6 col-md-4 mt-2 mt-sm-0">
    <div>
        <label for="to_date">{{ translate('Max Price') }}</label>
        <input type="number" name="max" class="js-flatpickr form-control flatpickr-custom min-h-40px" placeholder="200">
    </div>
</div>

                            <div class="col-sm-6 col-md-3 mt-2 mt-sm-0 __btn-row">
    <a href="http://livart.app/backend/admin/product/list" id="clearButton" class="btn w-100 btn--reset min-h-45px">{{ translate('clear') }}</a>
    <button type="submit" class="btn btn-primary btn-block">{{ translate('Show_Data') }}</button>
</div>

                        </div>
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">

                                    <div class="input-group">
                                        <select id="orderSelect" class="custom-select min-h-40" name="order">
                                            <option value="all">{{ translate('All fields') }}</option>
                                            <option value="id">{{ translate('ID') }}</option>
                                            <option value="barcode">{{ translate('BarCode') }}</option>
                                            <option value="name">{{ translate('Name') }}</option>
                                            <option value="stock">{{ translate('Stock') }}</option>
                                        </select>
                                        <div class="col-sm-6 col-md-3" id="stockSelect" style="display: none;">
                                            <select class="custom-select min-h-40" name="stock">
                                                <option value="all">{{ translate('All') }}</option>
                                                <option value="low">{{ translate('Low') }}</option>
                                                <option value="medium">{{ translate('Medium') }}</option>
                                                <option value="full">{{ translate('Full') }}</option>
                                            </select>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control min-h-40" placeholder="{{translate('Search...')}}" aria-label="Search" value="{{$search}}" autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">{{translate('search')}}
                                            </button>
                                        </div>
                                    </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var orderSelect = document.getElementById('orderSelect');
                                        var searchInput = document.getElementById('datatableSearch_');
                                        var stockSelect = document.getElementById('stockSelect');
                                        var paymentStatusSelect = document.getElementById('paymentStatusSelect');
                                        var selectElements = document.querySelectorAll('select');
                                        selectElements.forEach(function(select) {
                                            select.addEventListener('change', function() {
                                                // Vide le champ de recherche
                                                searchInput.value = '';
                                            });
                                        });

                                        // Écoute l'événement de changement sur le select "État de paiement"
                                        orderSelect.addEventListener('change', function() {
                                            if (this.value === 'stock') {
                                                // Masque le champ de recherche si "Payment Status" est sélectionné
                                                searchInput.style.display = 'none';
                                                stockSelect.style.display = 'block';
                                            } else {
                                                // Affiche le champ de recherche si une autre option est sélectionnée
                                                searchInput.style.display = 'block';
                                                stockSelect.style.display = 'none';
                                            }
                                        });

                                        if (this.value === 'stock') {
                                            // Masque le champ de recherche si "Payment Status" est sélectionné
                                            searchInput.style.display = 'none';
                                            stockSelect.style.display = 'block';
                                        } else {
                                            // Affiche le champ de recherche si une autre option est sélectionnée
                                            searchInput.style.display = 'block';
                                            stockSelect.style.display = 'none';
                                        }
                                    });
                                </script>
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
                                            window.location.href = 'http://livart.app/backend/admin/product/list'; // Redirige vers la route spécifiée
                                        }
                                    });

                                    // Prevent the form from being submitted if the select value is not changed
                                    document.getElementById('searchForm').addEventListener('submit', function(event) {
                                        if (orderSelect.value === currentOrder) {
                                            event.preventDefault();
                                        }
                                    });

                                </script>
                            </div>
                            <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                <a href="{{route('admin.product.add-new')}}" class="btn btn-primary">
                                    <i class="tio-add"></i>
                                    {{translate('add_new_product')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    </form>

                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{translate('ID')}}</th>
                                    <th>{{translate('BarCode')}}</th>
                                    <th>{{translate('product_name')}}</th>
                                    <th>{{translate('status')}}</th>
                                    <th>{{translate('price')}}</th>
                                    <th>{{translate('stock')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($products as $key=>$product)
                                <tr>
                                    <td>{{$product['id']}}</td>
                                    <td>
                                        {{$product['codebar']}}
                                        {!! (new Picqer\Barcode\BarcodeGeneratorHTML())->getBarcode($product['codebar'], Picqer\Barcode\BarcodeGeneratorHTML::TYPE_CODE_128) !!}
                                    </td>
                                    <td>
                                        <div class="media gap-3 align-items-center">
                                            <div class="avatar rounded border">
                                                <img
                                                    src="{{$product['image_fullpath'][0]}}"
                                                     class="img-fit rounded"
                                                    alt="{{ translate('product') }}">
                                            </div>
                                             <a href="{{route('admin.product.view',[$product['id']])}}" class="media-body text-dark">
                                               {{substr($product['name'],0,20)}}{{strlen($product['name'])>20?'...':''}}
                                             </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product['status']==1)
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input change-status" checked id="{{$product['id']}}"
                                                       data-route="{{route('admin.product.status',[$product['id'],0])}}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        @else
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input change-status"  id="{{$product['id']}}"
                                                       data-route="{{route('admin.product.status',[$product['id'],1])}}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>{{ Helpers::set_symbol($product['price']) }}</td>
                                    <td>
                                        <!-- HTML pour l'affichage de l'alerte de stock -->
                                        @if($product['total_stock'] < 5)
                                            <div class="alert alert-danger stock-alert" role="alert" data-toggle="modal" data-target="#stockModal{{$product->id}}">
                                                Low Stock: {{$product['total_stock']}}
                                            </div>
                                        @elseif($product['total_stock'] >= 5 && $product['total_stock'] < 10)
                                            <div class="alert alert-warning stock-alert" role="alert" data-toggle="modal" data-target="#stockModal{{$product->id}}">
                                                Medium Stock: {{$product['total_stock']}}
                                            </div>
                                        @else
                                            <div class="alert alert-success stock-alert" role="alert" data-toggle="modal" data-target="#stockModal{{$product->id}}">
                                                Full Stock : {{$product['total_stock']}}
                                            </div>
                                        @endif

                           

                                        <script>
                                            // JavaScript pour afficher le modal lorsqu'un utilisateur clique sur l'alerte de stock
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const stockAlerts = document.querySelectorAll('.stock-alert');
                                                stockAlerts.forEach(function(alert) {
                                                    alert.addEventListener('click', function() {
                                                        const modalId = this.getAttribute('data-target');
                                                        const modal = document.querySelector(modalId);
                                                        if (modal) {
                                                            $(modalId).modal('show');
                                                        }
                                                    });
                                                });
                                            });
                                        </script>


                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a class="btn btn-outline-primary square-btn"
                                                href="{{route('admin.product.edit',[$product['id']])}}">
                                                <i class="tio tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger square-btn form-alert" href="javascript:"
                                               data-id="product-{{$product['id']}}"
                                               data-message="{{translate('Want to delete this product ?')}}">
                                                <i class="tio tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.product.delete',[$product['id']])}}"
                                                method="post" id="product-{{$product['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                                
                                
                                         <!-- Modal -->
                                        <div class="modal fade" id="stockModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel{{$product->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="stockModalLabel{{$product->id}}">Edit du stock</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('admin.product.handleUpdateStock',$product->id)}}" method="post">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-body">
                                                            <label for="stock">Stock :</label>
                                                            <input type="hidden" name="product_id" value="{{$product['id']}}">
                                                            <input type="number" name="total_stock" id="stock" class="form-control" value="{{$product['total_stock']}}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $products->links() !!}
                        </div>
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 width-7rem" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
       
@endsection

