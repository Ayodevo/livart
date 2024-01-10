<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        private Order $order,
        private OrderDetail $order_detail,
        private DeliveryMan $delivery_man,
        private Branch $branch,
        private Product $product,
    ){}

    /**
     * @return Application|Factory|View
     */
    public function order_index(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        return view('admin-views.report.order-index');
    }

    /**
     * @return Application|Factory|View
     */
    public function earning_index(): Factory|View|Application
    {
        if (!session()->has('from_date')) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.earning-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function set_date(Request $request): RedirectResponse
    {
        $fromDate = \Carbon\Carbon::parse($request['from'])->startOfDay();
        $toDate = Carbon::parse($request['to'])->endOfDay();

        session()->put('from_date', $fromDate);
        session()->put('to_date', $toDate);
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function driver_report(Request $request): Factory|View|Application
    {
        $deliveryman_id = $request['delivery_man_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $delivery_men = $this->delivery_man->all();

        $orders = $this->order->with('delivery_man')
            ->where('order_status', 'delivered')
            ->whereNotNull('delivery_man_id')
            ->when((!is_null($deliveryman_id) && $deliveryman_id != 'all'), function ($query) use ($deliveryman_id) {
                return $query->where('delivery_man_id', $deliveryman_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->paginate(Helpers::pagination_limit());

        return view('admin-views.report.deliveryman-report-index', compact('delivery_men','orders', 'deliveryman_id', 'start_date', 'end_date'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function driver_filter(Request $request): JsonResponse
    {
        $fromDate = Carbon::parse($request->formDate)->startOfDay();
        $toDate = Carbon::parse($request->toDate)->endOfDay();

        $orders = $this->order->with('delivery_man')
            ->where(['order_status' => 'delivered'])
            ->where(['delivery_man_id' => $request['delivery_man']])
            ->whereBetween('created_at', [$fromDate, $toDate])->get();

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
            'delivered_qty' => $orders->count()
        ]);

    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function product_report(Request $request): Factory|View|Application
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $branch_id = $request['branch_id'];
        $product_id = $request['product_id'];

        $branches = $this->branch->all();
        $products = $this->product->all();

        $orders = $this->order->with(['branch', 'details'])
            ->where('order_status', 'delivered')
            ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->get();

        $data = [];
        $total_sold = 0;
        $total_qty = 0;

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                if ($detail['product_id'] == $request['product_id']) {
                    $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
                    $ord_total = $price * $detail['quantity'];
                    $data[] = [
                        'order_id' => $order['id'],
                        'date' => $order['created_at'],
                        'customer' => $order->customer,
                        'price' => $ord_total,
                        'quantity' => $detail['quantity'],
                    ];
                    $total_sold += $ord_total;
                    $total_qty += $detail['quantity'];
                }
            }
        }

        return view('admin-views.report.product-report', compact('data', 'total_sold', 'total_qty', 'branches', 'products', 'start_date', 'end_date', 'branch_id', 'product_id'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function product_report_filter(Request $request): JsonResponse
    {
        $fromDate = \Carbon\Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        $orders = $this->order->where(['branch_id' => $request['branch_id']])
            ->whereBetween('created_at', [$fromDate, $toDate])->get();

        $data = [];
        $total_sold = 0;
        $total_qty = 0;
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                if ($detail['product_id'] == $request['product_id']) {
                    $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
                    $ord_total = $price * $detail['quantity'];
                    $data[] = [
                        'order_id' => $order['id'],
                        'date' => $order['created_at'],
                        'customer' => $order->customer,
                        'price' => $ord_total,
                        'quantity' => $detail['quantity'],
                    ];
                    $total_sold += $ord_total;
                    $total_qty += $detail['quantity'];
                }
            }
        }

        session()->put('export_data', $data);

        return response()->json([
            'order_count' => count($data),
            'item_qty' => $total_qty,
            'order_sum' => $total_sold,
            'view' => view('admin-views.report.partials._table', compact('data'))->render(),
        ]);

    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_product_report(Request $request): StreamedResponse|string
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $branch_id = $request['branch_id'];
        $product_id = $request['product_id'];

        $orders = $this->order->with(['branch', 'details'])
            ->where('order_status', 'delivered')
            ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->get();

        $data = [];

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                if ($detail['product_id'] == $request['product_id']) {
                    $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
                    $ord_total = $price * $detail['quantity'];
                    $data[] = [
                        'Order Id' => $order['id'],
                        'Date' => $order->created_at,
                        'Quantity' => $detail['quantity'],
                        'Amount' => $ord_total,
                    ];
                }
            }
        }
        return (new FastExcel($data))->download('product-report.xlsx');
    }

    /**
     * @return Application|Factory|View
     */
    public function sale_report(Request $request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $branch_id = $request['branch_id'];

        $branches = $this->branch->all();

        $orders = $this->order->with(['branch', 'details'])
            ->where('order_status', 'delivered')
            ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->pluck('id')->toArray();

       // return $orders;

        $data = [];
        $total_sold = 0;
        $total_qty = 0;

        $order_details = $this->order_detail->whereIn('order_id', $orders)->latest()->get();

        foreach ($order_details as $detail) {
            $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];
            $data[] = [
                'order_id' => $detail['order_id'],
                'date' => $detail['created_at'],
                'price' => $ord_total,
                'quantity' => $detail['quantity'],
            ];
            $total_sold += $ord_total;
            $total_qty += $detail['quantity'];
        }

        return view('admin-views.report.sale-report', compact('orders', 'data', 'total_sold', 'total_qty', 'start_date', 'end_date', 'branch_id', 'branches'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sale_filter(Request $request): JsonResponse
    {
        $from = $to = null;

        if (!is_null($request->from) && !is_null($request->to))
        {
            $from = Carbon::parse($request->from)->format('Y-m-d');
            $to = Carbon::parse($request->to)->format('Y-m-d');
        }

        $orders = $this->order->where(['order_status' => 'delivered']);

        if ($request['branch_id'] != 'all') {
            $orders = $orders->where(['branch_id' => $request['branch_id']]);
        }

        $orders = $orders->when((!is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
            return $query->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        })->pluck('id')->toArray();

        $data = [];
        $total_sold = 0;
        $total_qty = 0;

        foreach ($this->order_detail->whereIn('order_id', $orders)->get() as $detail) {
            $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];
            $data[] = [
                'order_id' => $detail['order_id'],
                'date' => $detail['created_at'],
                'price' => $ord_total,
                'quantity' => $detail['quantity'],
            ];
            $total_sold += $ord_total;
            $total_qty += $detail['quantity'];
        }

        return response()->json([
            'order_count' => count($orders),
            'item_qty' => $total_qty,
            'order_sum' => $total_sold,
            'view' => view('admin-views.report.partials._table', compact('data'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_sale_report(Request $request): StreamedResponse|string
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $branch_id = $request['branch_id'];

        $orders = $this->order->with(['branch', 'details'])
            ->where('order_status', 'delivered')
            ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->pluck('id')->toArray();

        $data = [];

        foreach ($this->order_detail->whereIn('order_id', $orders)->get() as $detail) {
            $price = Helpers::variation_price(json_decode($detail->product_details, true), $detail['variations']) - $detail['discount_on_product'];
            $ord_total = $price * $detail['quantity'];
            $data[] = [
                'Order Id' => $detail['order_id'],
                'Date' => $detail['created_at'],
                'Quantity' => $detail['quantity'],
                'Price' => $ord_total,
            ];
        }
        return (new FastExcel($data))->download('sale-report.xlsx');
    }
}
