<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'price',
        'description',
        'img',
        'quantity',
        'availability',
        'cantina_id'
      ];

      public function cantinas()
      {
          return $this->belongsTo(Cantina::class);
      }

      public function orders()
      {
          return $this->belongsToMany(Order::class, 'order_product')
                      ->withPivot('quantity')
                      ->withTimestamps();
      }
  
  

}