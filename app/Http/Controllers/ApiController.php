<?php

namespace App\Http\Controllers;

use App\Models\{Order,Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function health(){return response()->json(['status'=>'ok','application'=>config('app.name'),'database'=>$this->databaseHealthy(),'timestamp'=>now()->toIso8601String()]);}
    private function databaseHealthy():bool{try{DB::connection()->getPdo();return true;}catch(\Throwable){return false;}}
    public function products(Request $r){return Product::with(['category','reviews'])->where('status',true)->when($r->search,fn($q,$s)=>$q->where(fn($x)=>$x->where('name','like','%'.$s.'%')->orWhere('description','like','%'.$s.'%')))->when($r->category,fn($q,$c)=>$q->where('category_id',$c))->paginate(min(max((int)$r->input('per_page',20),1),100));}
    public function product(Product $product){abort_unless($product->status,404);return $product->load(['category','reviews','variants','images','questions']);}
    public function orders(Request $r){return $r->user()->orders()->with('items')->latest()->paginate(20);}
    public function order(Request $r,Order $order){abort_unless($order->user_id===$r->user()->id,403);return $order->load('items');}
}
