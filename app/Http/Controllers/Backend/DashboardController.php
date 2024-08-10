<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    //
    public function dashboard(){
        $sales_monthly = Order::whereMonth('created_at',date('m'))->whereYear('created_at', date('Y'))->sum('total_amount');
        $sales_yearly = Order::whereYear('created_at', date('Y'))->sum('total_amount');
        $purchase_monthly = OrderDetail::whereMonth('created_at',date('m'))->whereYear('created_at', date('Y'))->sum('purchase_price');
        $orders = Order::whereMonth('created_at',date('m'))->whereYear('created_at', date('Y'))->count();
        if($sales_monthly > 0){
        	$profit = round(($sales_monthly - $purchase_monthly) / $orders, 0);
        }
        else{
        	$profit = 0;
        }

        // Fetching orders for the last 7 days or user-supplied range
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
        return view('backend.index',compact('sales_monthly','sales_yearly','orders','profit','totals','dates','purchase_monthly'));
    }


    public function rolesPermissions(){
        $roles = Role::all();
        return view('backend.role.index',compact('roles'));
    }

    public function assignPermission(Request $request){

        $hasPermission = DB::table('role_has_permissions')->where('role_id',$request->role)->where('permission_id',$request->permission_id)->first();
        $role = Role::where('id', $request->role)->first();
        if($hasPermission == null){
            $permission = Permission::where('id',$request->permission)->first();
            $role->givePermissionTo($permission->name);
            noty()->success('Permission Updated Successfully');
        }
        else{
            noty()->error('Already have the permission');
        }
        
        
        return redirect()->back();
    }
}
