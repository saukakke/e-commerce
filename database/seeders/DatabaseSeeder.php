<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories=['Electronics','Fashion','Home & Living','Beauty'];
        foreach($categories as $name){
            $category=Category::create(['name'=>$name,'slug'=>Str::slug($name),'description'=>'Quality '.$name.' products.']);
            for($i=1;$i<=4;$i++) Product::create(['category_id'=>$category->id,'name'=>$name.' Product '.$i,'slug'=>Str::slug($name.' Product '.$i), 'sku'=>strtoupper(Str::random(8)), 'description'=>'A carefully selected '.$name.' item for everyday use.','price'=>rand(30,300)*1000,'discount_price'=>$i%2===0?rand(20,250)*1000:null,'stock'=>rand(5,50),'image'=>'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=900','featured'=>$i===1]);
        }
    }
}