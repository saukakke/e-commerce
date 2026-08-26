<?php
namespace App\Http\Controllers;
use App\Models\Order; use Illuminate\Http\Request;
class OrderController extends Controller { public function index(){ return view('account.orders',['orders'=>auth()->user()->orders()->latest()->paginate(10)]); } public function show(Order $order){ abort_unless($order->user_id===auth()->id(),403); return view('account.order',['order'=>$order->load('items')]); } }
