<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    public function initialize(Order $order): string
    {
        if ($order->payment_method !== 'paystack') throw new RuntimeException('Online payment is not selected.');
        $secret = config('services.paystack.secret');
        if (!$secret) throw new RuntimeException('Paystack is not configured.');

        $reference = 'SH-'.strtoupper(Str::random(20));
        $transaction = PaymentTransaction::create(['order_id'=>$order->id,'reference'=>$reference,'amount'=>$order->total,'currency'=>$order->currency ?? 'NGN','status'=>'pending']);
        $response = Http::withToken($secret)->acceptJson()->post('https://api.paystack.co/transaction/initialize', [
            'email'=>$order->user?->email,'amount'=>(int) round($order->total*100),'reference'=>$reference,
            'callback_url'=>route('payment.callback'),'metadata'=>['order_id'=>$order->id],
        ]);
        if (!$response->successful() || !$response->json('status')) {
            $transaction->update(['status'=>'failed','payload'=>$response->json()]);
            throw new RuntimeException($response->json('message','Unable to initialize payment.'));
        }
        $transaction->update(['payload'=>$response->json()]);
        return $response->json('data.authorization_url');
    }

    public function confirmWebhook(string $reference, array $data): bool
    {
        return DB::transaction(function () use ($reference,$data) {
            $transaction=PaymentTransaction::where('reference',$reference)->lockForUpdate()->first();
            if (!$transaction) return false;
            if ($transaction->status==='paid') return true;
            if (($data['status'] ?? null)!=='success') { $transaction->update(['payload'=>$data,'status'=>'failed']); return true; }
            $expected=(int) round((float)$transaction->amount*100);
            if ((int)($data['amount']??-1)!==$expected || ($data['currency']??'')!==($transaction->currency??'NGN')) {
                $transaction->update(['payload'=>$data,'status'=>'failed']); return false;
            }
            $transaction->update(['status'=>'paid','payload'=>$data,'paid_at'=>now()]);
            $order=$transaction->order()->lockForUpdate()->first();
            if ($order && $order->payment_status!=='paid') $order->update(['payment_status'=>'paid','status'=>'processing','paid_at'=>now()]);
            return true;
        });
    }
}
