<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','number','status','payment_method','payment_status','subtotal','discount','shipping','total','coupon_code','shipping_name','shipping_phone','shipping_address','shipping_city','shipping_state','notes'];
    protected $casts = ['subtotal'=>'decimal:2','discount'=>'decimal:2','shipping'=>'decimal:2','total'=>'decimal:2'];
    public function user(){ return $this->belongsTo(User::class); }
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function getStatusLabelAttribute(){ return ucfirst(str_replace('_',' ',$this->status)); }
}
