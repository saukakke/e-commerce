<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaymentService
{
    public function initialize(Order $order): string
    {
        if ($order->payment_method !== 'paystack') throw new RuntimeException('Online payment is not selected.');
        $secret=config('services.paystack.secret');
        if (!$secret) throw new RuntimeException('Paystack is not configured.');
        $transaction=PaymentTransaction::firstOrCreate(['reference'=>$order->number],['order_id'=>$order->id,'provider'=>'paystack','amount'=>$order->total,'currency'=>$order->currency??'NGN','status'=>'pending']);
        if ($transaction->status==='paid') return route('orders.show',$order);
        $response=Http::withToken($secret)->acceptJson()->post('https://api.paystack.co/transaction/initialize',['email'=>$order->user?->email,'amount'=>(int)round($order->total*100),'reference'=>$transaction->reference,'callback_url'=>route('payment.callback'),'metadata'=>['order_id'=>$order->id]]);
        if(!$response->successful()||!$response->json('status')){$transaction->update(['status'=>'failed','payload'=>$response->json()]);throw new RuntimeException($response->json('message','Unable to initialize payment.'));}
        $transaction->update(['payload'=>$response->json(),'status'=>'pending']);
        return $response->json('data.authorization_url');
    }

    public function verify(string $reference): ?Order
    {
        $secret=config('services.paystack.secret');
        if(!$secret)return null;
        $transaction=PaymentTransaction::where('reference',$reference)->first();
        if(!$transaction)return null;
        $response=Http::withToken($secret)->acceptJson()->get('https://api.paystack.co/transaction/verify/'.urlencode($reference));
        if(!$response->successful()||$response->json('data.status')!=='success')return null;
        $data=$response->json('data',[]);
        return $this->confirmWebhook($reference,$data)?$transaction->order()->first():null;
    }

    public function confirmWebhook(string $reference,array $data): bool
    {
        return DB::transaction(function()use($reference,$data){
            $transaction=PaymentTransaction::where('reference',$reference)->lockForUpdate()->first();
            if(!$transaction)return false;
            if($transaction->status==='paid')return true;
            if(($data['status']??null)!=='success'){$transaction->update(['payload'=>$data,'status'=>'failed']);return true;}
            $expected=(int)round((float)$transaction->amount*100);
            if((int)($data['amount']??-1)!==$expected||($data['currency']??'')!==($transaction->currency??'NGN')){$transaction->update(['payload'=>$data,'status'=>'failed']);return false;}
            $transaction->update(['status'=>'paid','payload'=>$data,'paid_at'=>now()]);
            $order=$transaction->order()->lockForUpdate()->first();
            if($order&&$order->payment_status!=='paid')$order->update(['payment_status'=>'paid','status'=>'processing','paid_at'=>now()]);
            return true;
        });
    }
}
