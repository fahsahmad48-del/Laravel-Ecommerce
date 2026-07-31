<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_Product extends Model
{
    protected $table = 'category_product';
    public $timestamps = false;

    public function category(){
    return $this->belongsTo(Category::class);
}

public function product(){
    return $this->belongsTo(Product::class);
}

}
