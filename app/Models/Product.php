<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'main_img',
        'details',
        'category_id'
    ];

    public function categories(){
    return $this->hasMany(Category::class);
}

}
