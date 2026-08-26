<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home() {
        return view('shop.home', [
            'featured' => Product::where('status', true)->where('featured', true)->latest()->take(8)->get(),
            'products' => Product::where('status', true)->latest()->paginate(12),
            'categories' => Category::where('status', true)->withCount('products')->get(),
        ]);
    }

    public function products(Request $request) {
        $query = Product::where('status', true)->with('category');
        if ($request->filled('q')) $query->where(fn($q) => $q->where('name','like','%'.$request->q.'%')->orWhere('description','like','%'.$request->q.'%'));
        if ($request->filled('category')) $query->whereHas('category', fn($q) => $q->where('slug',$request->category));
        if ($request->filled('min')) $query->whereRaw('COALESCE(discount_price, price) >= ?', [$request->min]);
        if ($request->filled('max')) $query->whereRaw('COALESCE(discount_price, price) <= ?', [$request->max]);
        $query->orderBy(match($request->get('sort')) {'price_asc'=>'price','price_desc'=>'price','name'=>'name',default=>'created_at'}, $request->get('sort') === 'price_asc' ? 'asc' : 'desc');
        return view('shop.products', ['products'=>$query->paginate(12)->withQueryString(),'categories'=>Category::where('status',true)->get()]);
    }

    public function show(Product $product) {
        abort_unless($product->status, 404);
        return view('shop.show', ['product'=>$product, 'related'=>Product::where('category_id',$product->category_id)->whereKeyNot($product->id)->where('status',true)->take(4)->get()]);
    }
}