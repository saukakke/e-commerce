<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\VendorPayout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class VendorPayoutService
{
    public function request(Vendor $vendor, array $data): VendorPayout
    {
        return DB::transaction(function () use ($vendor,$data) {
            $vendor=Vendor::whereKey($vendor->id)->lockForUpdate()->firstOrFail();
            $reserved=VendorPayout::where('vendor_id',$vendor->id)->whereIn('status',['requested','processing'])->sum('amount');
            if ((float)$data['amount'] > ((float)$vendor->balance-(float)$reserved)) throw new RuntimeException('Insufficient available vendor balance.');
            return VendorPayout::create($data+['vendor_id'=>$vendor->id,'reference'=>'PO-'.strtoupper(Str::random(12)),'status'=>'requested']);
        });
    }

    public function approve(VendorPayout $payout): VendorPayout
    {
        return DB::transaction(function () use ($payout) {
            $payout=VendorPayout::whereKey($payout->id)->lockForUpdate()->firstOrFail();
            if ($payout->status!=='requested') throw new RuntimeException('Only requested payouts can be approved.');
            $vendor=Vendor::whereKey($payout->vendor_id)->lockForUpdate()->firstOrFail();
            if ((float)$vendor->balance < (float)$payout->amount) throw new RuntimeException('Vendor balance is insufficient.');
            $vendor->decrement('balance',$payout->amount);
            $payout->update(['status'=>'processing']);
            return $payout->fresh();
        });
    }

    public function complete(VendorPayout $payout): VendorPayout
    {
        return DB::transaction(function () use ($payout) {
            $payout=VendorPayout::whereKey($payout->id)->lockForUpdate()->firstOrFail();
            if ($payout->status!=='processing') throw new RuntimeException('Only processing payouts can be completed.');
            $payout->update(['status'=>'paid','paid_at'=>now()]);
            return $payout->fresh();
        });
    }

    public function reject(VendorPayout $payout, ?string $reason=null): VendorPayout
    {
        return DB::transaction(function () use ($payout,$reason) {
            $payout=VendorPayout::whereKey($payout->id)->lockForUpdate()->firstOrFail();
            if (!in_array($payout->status,['requested','processing'],true)) throw new RuntimeException('Payout cannot be rejected in its current state.');
            if ($payout->status==='processing') Vendor::whereKey($payout->vendor_id)->lockForUpdate()->firstOrFail()->increment('balance',$payout->amount);
            $payout->update(['status'=>'rejected','rejection_reason'=>$reason]);
            return $payout->fresh();
        });
    }
}
