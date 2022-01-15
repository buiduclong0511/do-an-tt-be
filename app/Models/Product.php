<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'price',
        'image',
        'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function Carts() {
        return $this->belongsToMany(Cart::class, 'product_cart', 'product_id', 'cart_id');
    }
}
