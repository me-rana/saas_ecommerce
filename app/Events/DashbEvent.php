<?php

namespace App\Events;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DashbEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $sales_monthly;
    public $sales_yearly;
    public $purchase_monthly;
    public $profit;
    public $orders;
    public $totals;
    public $dates;

    public function __construct()
    {
        //
        $this->sales_monthly = Order::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');
        $this->sales_yearly = Order::whereYear('created_at', date('Y'))
            ->sum('total_amount');
        $this->purchase_monthly = OrderDetail::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('purchase_price');
        $this->orders = Order::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        $this->profit = $this->orders ? round(($this->sales_monthly - $this->purchase_monthly) / $this->orders, 0) : 0;
        $graphStartDate = Carbon::today()->subDays(6);
        $graphEndDate = Carbon::tomorrow();
        $orders_data = Order::whereBetween('created_at', [$graphStartDate, $graphEndDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
    
        // Prepare data for Chart.js
        $dates = [];
        $totals = [];
        foreach ($orders_data as $order) {
            $dates[] = $order->date;
            $totals[] = $order->total;
        }
        $this->dates = $dates;
        $this->totals = $totals;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

     public function broadcastWith(): array
     {
         return [
            'sales_monthly' => $this->sales_monthly,
            'sales_yearly' => $this->sales_yearly,
            'purchase_monthly' => $this->purchase_monthly,
            'profit' => $this->profit,
            'orders' => $this->orders,
            'dates' => $this->dates,
            'orders' =>$this->totals,
         ];
     }
    public function broadcastOn(): array
    {
        return [
            new Channel('DashbChannel'),
        ];
    }
}
