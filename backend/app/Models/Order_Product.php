<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_price',
        'quantity',
        'order_id',
        'product_id'
      ];
}
