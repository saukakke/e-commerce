<?php

namespace App\Services;

use App\Models\ShippingZone;

class ShippingService
{
    public function calculate(float $subtotal,string $state):array
    {
        $zones=ShippingZone::where('active',true)->get();
        $normalized=mb_strtolower(trim($state));
        $zone=$zones->first(fn($z)=>collect($z->states??[])->contains(fn($s)=>mb_strtolower(trim($s))===$normalized));
        $zone??=$zones->first(fn($z)=>empty($z->states));
        if(!$zone)return ['amount'=>0.0,'zone'=>null,'estimated_days'=>null,'courier'=>null];
        $rate=(float)$zone->calculate($subtotal);
        $shippingRate=$zone->rates()->where('active',true)->orderBy('rate')->first();
        return ['amount'=>$rate,'zone'=>$zone,'estimated_days'=>$shippingRate?->estimated_days??$zone->estimated_days,'courier'=>$shippingRate?->courier:null];
    }
}
