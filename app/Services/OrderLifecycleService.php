<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderLifecycleService
{
    private const TRANSITIONS = [
        'pending'=>['processing','cancelled'],
        'processing'=>['shipped','cancelled'],
        'shipped'=>['delivered'],
        'delivered'=>[],
        'cancelled'=>[],
        'refunded'=>[],
    ];

    public function transition(Order $order, string $status): Order
    {
        return DB::transaction(function () use ($order,$status) {
            $current=$order->status;
            if ($current===$status) return $order;
            if (!in_array($status,self::TRANSITIONS[$current]??[],true)) throw new InvalidArgumentException("Invalid order transition from {$current} to {$status}.");
            $order=Order::whereKey($order->id)->lockForUpdate()->with('items.product')->firstOrFail();
            if ($status==='cancelled') foreach($order->items as $item) if($item->product) Product::whereKey($item->product_id)->lockForUpdate()->increment('stock',$item->quantity);
            $fields=['status'=>$status];
            if($status==='shipped')$fields['shipped_at']=now();
            if($status==='delivered')$fields['delivered_at']=now();
            if($status==='cancelled')$fields['cancelled_at']=now();
            $order->update($fields);
            return $order->fresh();
        });
    }
}
