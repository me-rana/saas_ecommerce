<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //

    public function orders(){
        $perpage = 50;
        $orders = Order::latest()->paginate($perpage);
        $order_statuses = OrderStatus::where('status',1)->get();
        return view('backend.order.index',compact('orders','order_statuses'));
    }

    public function orderDetails($id){
        $order = Order::where('id',$id)->first();
        $order_statuses = OrderStatus::where('status',1)->get();
        return view('backend.order.details',compact('order','order_statuses'));
    }

    public function orderStatus(Request $request){
        $order = Order::where('id',$request->order_id)->first();
        $order->update([
            'status' => $request->status,
        ]);
        noty()->success('Order Status updated Successfully');
        return redirect()->back();
    }
}
