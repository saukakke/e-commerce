<?php
namespace App\Http\Controllers;
use App\Models\Category; use App\Models\Product; use Illuminate\Http\Request;
class ShopController extends Controller
{
 public function home(){return view('shop.home',['featured'=>Product::where('status',true)->where('featured',true)->latest()->take(8)->get(),'products'=>Product::where('status',true)->latest()->paginate(12),'categories'=>Category::where('status',true)->withCount('products')->get()]);}
 public function products(Request $r){$query=Product::where('status',true)->with('category');if($r->filled('q'))$query->where(fn($q)=>$q->where('name','like','%'.$r->q.'%')->orWhere('description','like','%'.$r->q.'%')->orWhere('sku','like','%'.$r->q.'%'));if($r->filled('category'))$query->whereHas('category',fn($q)=>$q->where('slug',$r->category));if($r->filled('min'))$query->whereRaw('COALESCE(discount_price,price)>=?',[$r->min]);if($r->filled('max'))$query->whereRaw('COALESCE(discount_price,price)<=?',[$r->max]);$sort=$r->get('sort');$query->orderBy(in_array($sort,['price_asc','price_desc'])?'price':($sort==='name'?'name':'created_at'),in_array($sort,['price_asc','name'])?'asc':'desc');return view('shop.products',['products'=>$query->paginate(12)->withQueryString(),'categories'=>Category::where('status',true)->get()]);}
 public function show(Product $product){abort_unless($product->status,404);return view('shop.show',['product'=>$product->load(['category','reviews'=>fn($q)=>$q->where('approved',true)->with('user')->latest()]),'related'=>Product::where('category_id',$product->category_id)->whereKeyNot($product->id)->where('status',true)->take(4)->get()]);}
}
