<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','slug','sku','description','price','discount_price','stock','image','featured','status'];

    protected $casts = ['price'=>'decimal:2','discount_price'=>'decimal:2','featured'=>'boolean','status'=>'boolean'];

    public function category() { return $this->belongsTo(Category::class); }
    public function getSalePriceAttribute(): float { return (float) ($this->discount_price ?? $this->price); }
}