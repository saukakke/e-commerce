<?php
namespace App\Http\Controllers;
use App\Models\Product; use Illuminate\Http\Request;
class CartController extends Controller
{
 private function cart():array{return session('cart',[]);}
 public function index(){ $items=collect($this->cart())->map(fn($item,$id)=>array_merge($item,['id'=>$id]))->values();return view('shop.cart',['items'=>$items,'total'=>$items->sum(fn($i)=>$i['price']*$i['quantity'])]); }
 public function add(Request $r,Product $product){abort_unless($product->status&&$product->stock>0,404);$cart=$this->cart();$id=(string)$product->id;$qty=max(1,min((int)$r->input('quantity',1),$product->stock));if(isset($cart[$id]))$qty=min($cart[$id]['quantity']+$qty,$product->stock);$cart[$id]=['name'=>$product->name,'price'=>$product->sale_price,'quantity'=>$qty,'image'=>$product->image,'stock'=>$product->stock];session(['cart'=>$cart]);return back()->with('success','Product added to cart.');}
 public function update(Request $r,string $id){$cart=$this->cart();abort_unless(isset($cart[$id]),404);$qty=max(1,(int)$r->quantity);$cart[$id]['quantity']=min($qty,$cart[$id]['stock']);session(['cart'=>$cart]);return back()->with('success','Cart updated.');}
 public function remove(string $id){$cart=$this->cart();unset($cart[$id]);session(['cart'=>$cart]);return back()->with('success','Item removed.');}
}
