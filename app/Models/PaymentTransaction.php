<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable=['order_id','provider','reference','amount','currency','status','payload','paid_at'];
    protected $casts=['amount'=>'decimal:2','payload'=>'array','paid_at'=>'datetime'];
    public function order(){return $this->belongsTo(Order::class);}
}
