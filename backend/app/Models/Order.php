<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'status',
        'user_id',
        'cantina_id'
      ];

      public function cantinas()
      {
          return $this->belongsTo(Cantina::class);
      }

      public function users()
      {
          return $this->belongsTo(User::class);
      }

      public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
