<?php

namespace App\Http\Controllers;

use App\Models\{Vendor,ShippingZone,ShippingRate,PromotionCampaign,GiftCard,Dispute,ProductQuestion,VendorRating,VendorPayout};
use App\Services\VendorPayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class AdminPlatformController extends Controller
{
    public function vendors(){return view('admin.vendors',['vendors'=>Vendor::with('user')->latest()->paginate(20)]);}
    public function updateVendor(Request $r,Vendor $vendor){$d=$r->validate(['status'=>'required|in:pending,approved,suspended,rejected','commission_rate'=>'required|numeric|min:0|max:100']);$vendor->update($d);if($d['status']==='approved')$vendor->user->update(['role'=>'vendor']);return back()->with('success','Vendor updated.');}
    public function shipping(){return view('admin.shipping',['zones'=>ShippingZone::with('rates')->latest()->get()]);}
    public function storeZone(Request $r){$d=$r->validate(['name'=>'required|max:100','states'=>'nullable|string','rate'=>'required|numeric|min:0','free_shipping_minimum'=>'nullable|numeric|min:0','estimated_days'=>'required|integer|min:1']);$d['states']=$d['states']?array_values(array_filter(array_map('trim',explode(',',$d['states'])))):[];$d['active']=true;$z=ShippingZone::create($d);ShippingRate::create(['shipping_zone_id'=>$z->id,'courier'=>'Shoply Delivery','rate'=>$d['rate'],'estimated_days'=>$d['estimated_days'],'active'=>true]);return back()->with('success','Shipping zone created.');}
    public function campaigns(){return view('admin.campaigns',['campaigns'=>PromotionCampaign::latest()->paginate(20)]);}
    public function storeCampaign(Request $r){$d=$r->validate(['name'=>'required|max:190','type'=>'required|in:percent,fixed,free_shipping','value'=>'required|numeric|min:0','product_id'=>'nullable|exists:products,id','category_id'=>'nullable|exists:categories,id','starts_at'=>'nullable|date','ends_at'=>'nullable|date|after_or_equal:starts_at']);$d['active']=true;PromotionCampaign::create($d);return back()->with('success','Campaign created.');}
    public function giftCards(){return view('admin.giftcards',['cards'=>GiftCard::latest()->paginate(20)]);}
    public function createGiftCard(Request $r){$d=$r->validate(['amount'=>'required|numeric|min:1000|max:1000000','expires_at'=>'nullable|date']);$value=$d['amount'];GiftCard::create(['code'=>'GFT-'.strtoupper(Str::random(12)),'initial_value'=>$value,'balance'=>$value,'expires_at'=>$d['expires_at']??null,'active'=>true]);return back()->with('success','Gift card issued.');}
    public function disputes(){return view('admin.disputes',['disputes'=>Dispute::with(['user','order','vendor'])->latest()->paginate(20)]);}
    public function updateDispute(Request $r,Dispute $dispute){$d=$r->validate(['status'=>'required|in:open,in_review,resolved,rejected','resolution'=>'nullable|max:5000']);$dispute->update($d);return back()->with('success','Dispute updated.');}
    public function questions(){return view('admin.questions',['questions'=>ProductQuestion::with(['product','user'])->latest()->paginate(30)]);}
    public function updateQuestion(Request $r,ProductQuestion $question){$d=$r->validate(['answer'=>'nullable|max:5000','approved'=>'required|boolean']);$question->update($d);return back()->with('success','Question moderated.');}
    public function ratings(){return view('admin.ratings',['ratings'=>VendorRating::with(['vendor','user'])->latest()->paginate(30)]);}
    public function updateRating(Request $r,VendorRating $rating){$rating->update(['approved'=>$r->boolean('approved')]);return back()->with('success','Rating moderated.');}
    public function payouts(){return view('admin.payouts',['payouts'=>VendorPayout::with('vendor')->latest()->paginate(30)]);}
    public function updatePayout(Request $r,VendorPayout $payout,VendorPayoutService $service){$d=$r->validate(['status'=>'required|in:processing,paid,rejected','rejection_reason'=>'nullable|string|max:1000']);try{if($d['status']==='processing')$service->approve($payout);elseif($d['status']==='paid')$service->complete($payout);else $service->reject($payout,$d['rejection_reason']??null);}catch(RuntimeException $e){return back()->withErrors(['status'=>$e->getMessage()]);}return back()->with('success','Payout updated.');}
}
