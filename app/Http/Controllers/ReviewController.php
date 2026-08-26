<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Review; use Illuminate\Http\Request;
class ReviewController extends Controller { public function store(Request $request,Product $product){$data=$request->validate(['rating'=>'required|integer|min:1|max:5','comment'=>'nullable|string|max:1000']); Review::updateOrCreate(['user_id'=>auth()->id(),'product_id'=>$product->id],$data+['approved'=>true]); return back()->with('success','Your review has been saved.');} }
