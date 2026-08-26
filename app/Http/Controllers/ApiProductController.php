<?php
namespace App\Http\Controllers;
use App\Models\Product; use Illuminate\Http\Request;
class ApiProductController extends Controller { public function index(Request $r){return Product::where('status',true)->with('category')->paginate(min(50,max(1,(int)$r->get('per_page',12))));} }
