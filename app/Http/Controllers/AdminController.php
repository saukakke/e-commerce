<?php

namespace App\Http\Controllers;

use App\Models\{Category,Coupon,Order,OrderItem,Product,User};
use App\Services\OrderLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AdminController extends Controller
{
 public function dashboard(){return view('admin.dashboard',['orders'=>Order::latest()->take(8)->get(),'stats'=>['orders'=>Order::count(),'revenue'=>Order::where('payment_status','paid')->sum('total'),'products'=>Product::count(),'customers'=>User::where('role','customer')->count()]]);}
 public function products(){return view('admin.products',['products'=>Product::with('category')->latest()->paginate(15),'categories'=>Category::orderBy('name')->get()]);}
 public function storeProduct(Request $r){$d=$r->validate(['category_id'=>'nullable|exists:categories,id','name'=>'required|max:190','sku'=>'required|max:100|unique:products,sku','description'=>'nullable|string','price'=>'required|numeric|min:0','discount_price'=>'nullable|numeric|min:0|lt:price','stock'=>'required|integer|min:0','image'=>'nullable|url|max:500','featured'=>'nullable|boolean']);$d['slug']=Str::slug($d['name']).'-'.Str::lower(Str::random(5));$d['featured']=$r->boolean('featured');$d['status']=$r->boolean('status',true);Product::create($d);return back()->with('success','Product created.');}
 public function updateProduct(Request $r,Product $product){$d=$r->validate(['category_id'=>'nullable|exists:categories,id','name'=>'required|max:190','sku'=>'required|max:100|unique:products,sku,'.$product->id,'description'=>'nullable|string','price'=>'required|numeric|min:0','discount_price'=>'nullable|numeric|min:0|lt:price','stock'=>'required|integer|min:0','image'=>'nullable|url|max:500']);$d['featured']=$r->boolean('featured');$d['status']=$r->boolean('status');$product->update($d);return back()->with('success','Product updated.');}
 public function deleteProduct(Product $product){abort_if(OrderItem::where('product_id',$product->id)->exists(),422,'Ordered products cannot be deleted.');$product->delete();return back()->with('success','Product deleted.');}
 public function categories(){return view('admin.categories',['categories'=>Category::withCount('products')->orderBy('name')->get()]);}
 public function storeCategory(Request $r){$d=$r->validate(['name'=>'required|max:100|unique:categories,name','description'=>'nullable|string']);$d['slug']=Str::slug($d['name']);$d['status']=true;Category::create($d);return back()->with('success','Category created.');}
 public function deleteCategory(Category $category){abort_if($category->products()->exists(),422,'Remove its products first.');$category->delete();return back()->with('success','Category deleted.');}
 public function orders(){return view('admin.orders',['orders'=>Order::with('user')->latest()->paginate(20)]);}
 public function updateOrder(Request $r,Order $order,OrderLifecycleService $lifecycle){$d=$r->validate(['status'=>'required|in:pending,processing,shipped,delivered,cancelled,refunded','payment_status'=>'required|in:pending,paid,failed,refunded']);try{$lifecycle->transition($order,$d['status']);$order->update(['payment_status'=>$d['payment_status']]);}catch(InvalidArgumentException $e){return back()->withErrors(['status'=>$e->getMessage()]);}return back()->with('success','Order updated.');}
 public function coupons(){return view('admin.coupons',['coupons'=>Coupon::latest()->get()]);}
 public function storeCoupon(Request $r){$d=$r->validate(['code'=>'required|max:50|unique:coupons,code','type'=>'required|in:percent,fixed','value'=>'required|numeric|min:0','minimum_order'=>'nullable|numeric|min:0','usage_limit'=>'nullable|integer|min:1','starts_at'=>'nullable|date','expires_at'=>'nullable|date|after_or_equal:starts_at']);$d['code']=strtoupper($d['code']);$d['active']=true;Coupon::create($d);return back()->with('success','Coupon created.');}
 public function deleteCoupon(Coupon $coupon){$coupon->delete();return back()->with('success','Coupon deleted.');}
}
