<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $fillable = ['code','type','value','minimum_order','usage_limit','used_count','starts_at','expires_at','active'];
    protected $casts = ['value'=>'decimal:2','minimum_order'=>'decimal:2','starts_at'=>'datetime','expires_at'=>'datetime','active'=>'boolean'];
    public function validFor(float $subtotal): bool { $now=now(); return $this->active && $subtotal >= (float)$this->minimum_order && (!$this->starts_at || $now->gte($this->starts_at)) && (!$this->expires_at || $now->lte($this->expires_at)) && (!$this->usage_limit || $this->used_count < $this->usage_limit); }
    public function discount(float $subtotal): float { return $this->type === 'percent' ? min($subtotal, $subtotal * ((float)$this->value/100)) : min($subtotal,(float)$this->value); }
}
