<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\Transaction;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getOrderDetails():HasMany{
        return $this->hasMany(OrderDetail::class,'order_id');
    }

    public function getOrderStatus():BelongsTo{
        return $this->belongsTo(OrderStatus::class,'status','id');
    }

    public function getCustomer():BelongsTo{
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }

    public function getShipping():BelongsTo{
        return $this->belongsTo(ShippingAddress::class, 'shipping_id','id'); 
    }

    public function getPayment():BelongsTo{
        return $this->belongsTo(Transaction::class,'id','order_id');
    }

}
