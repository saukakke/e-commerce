<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Wishlist;
class WishlistController extends Controller { public function index(){return view('account.wishlist',['items'=>auth()->user()->wishlistItems()->with('product.category')->latest()->paginate(12)]);} public function toggle(Product $product){$w=Wishlist::where('user_id',auth()->id())->where('product_id',$product->id)->first(); if($w){$w->delete();$message='Removed from wishlist.';}else{Wishlist::create(['user_id'=>auth()->id(),'product_id'=>$product->id]);$message='Added to wishlist.';} return back()->with('success',$message);} }
