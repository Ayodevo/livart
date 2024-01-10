<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class SystemController extends Controller
{
    public function __construct(
        private Branch $branch,
        private Order $order
    ){}

    /**
     * @return Application|Factory|View
     */
    public function dashboard(): View|Factory|Application
    {
        $data = self::order_stats_data();

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        /*$earning = [];
        $earning_data = Order::where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            foreach ($earning_data as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = $match['sums'];
                }
            }
        }*/

        /** earning statistics chart **/

        $earning = [];
        $earning_data = $this->order->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])
            ->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            foreach ($earning_data as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = $match['sums'];
                }
            }
        }

        return view('branch-views.dashboard', compact('data', 'earning'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function order_stats(Request $request): JsonResponse
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::order_stats_data();

        return response()->json([
            'view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    /**
     * @return array
     */
    public function order_stats_data(): array
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = $this->order->where(['order_status' => 'pending', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $confirmed = $this->order->where(['order_status' => 'confirmed', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $processing = $this->order->where(['order_status' => 'processing', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $out_for_delivery = $this->order->where(['order_status' => 'out_for_delivery', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();

        $delivered = $this->order->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])->notPos()
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $all = $this->order->where(['branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $returned = $this->order->where(['order_status' => 'returned', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $failed = $this->order->where(['order_status' => 'failed', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $canceled = $this->order->where(['order_status' => 'canceled', 'branch_id' => auth('branch')->id()])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();


        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'all' => $all,
            'returned' => $returned,
            'failed' => $failed,
            'canceled' => $canceled,
        ];

        return $data;
    }

    /**
     * @return JsonResponse
     */
    public function restaurant_data(): JsonResponse
    {
        $new_order = DB::table('orders')->where(['branch_id' => auth('branch')->id(), 'checked' => 0])->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $new_order]
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): Factory|View|Application
    {
        return view('branch-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);

        $branch = $this->branch->find(auth('branch')->id());

        if ($request->has('image')) {
            $image_name =Helpers::update('branch/', $branch->image, 'png', $request->file('image'));
        } else {
            $image_name = $branch['image'];
        }

        $branch->name = $request->name;
        $branch->image = $image_name;
        $branch->save();
        Toastr::success(\App\CentralLogics\translate('Branch updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8|max:255',
            'confirm_password' => 'required|max:255',
        ]);

        $branch = $this->branch->find(auth('branch')->id());
        $branch->password = bcrypt($request['password']);
        $branch->save();
        Toastr::success(\App\CentralLogics\translate('Branch password updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_earning_statitics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $earning_data = array();
        if($dateType == 'yearEarn') {
            $number = 12;
            $from = \Illuminate\Support\Carbon::now()->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');

            $earning = $this->order->where(['order_status' => 'delivered', 'branch_id' => auth('branch')->id()])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['month'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }
            $key_range = array("Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

        }elseif($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d',strtotime($to));
            $key_range = range(1, $number);

            $earning = $this->order->where([
                'order_status' => 'delivered', 'branch_id' => auth('branch')->id()
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['day'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }

        }elseif($dateType == 'WeekEarn') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59');
            $date_range = CarbonPeriod::create($from, $to)->toArray();
            $day_range = array();
            foreach($date_range as $date){
                $day_range[] =$date->format('d');
            }
            $day_range = array_flip($day_range);
            $day_range_keys = array_keys($day_range);
            $day_range_values = array_values($day_range);
            $day_range_intKeys = array_map('intval', $day_range_keys);
            $day_range = array_combine($day_range_intKeys, $day_range_values);

            $earning = $this->order->where([
                'order_status' => 'delivered', 'branch_id' => auth('branch')->id()
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->orderBy('created_at', 'ASC')->pluck('sums', 'day')->toArray();

            $earning_data = array();
            foreach($day_range as $day=>$value){
                $day_value = 0;
                $earning_data[$day] = $day_value;
            }

            foreach($earning as $order_day => $order_value){
                if(array_key_exists($order_day, $earning_data)){
                    $earning_data[$order_day] = $order_value;
                }
            }

            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $label = $key_range;
        $earning_data_final = $earning_data;

        $data = array(
            'earning_label' => $label,
            'earning' => array_values($earning_data_final),
        );
        return response()->json($data);
    }

    public function ignore_check_order()
    {
        $this->order->where(['checked' => 0])->update(['checked' => 1]);
        return redirect()->back();
    }
}
