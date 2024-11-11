<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    protected $fillable = [
        'name',
        'price',
        'description',
        'img',
        'quantity',
        'availability',
        'cantina_id',
        'cost_price'
      ];

      public function cantina()
      {
          return $this->belongsTo(Cantina::class);
      }

      public function orders()
      {
          return $this->belongsToMany(Order::class, 'order_products')
                      ->withPivot('quantity')
                      ->withTimestamps();
      }
  
      public function registerMediaConversions(Media $media = null): void
      {
          $this->addMediaConversion('thumbProd')
              ->width(100)
              ->height(100);
      }
  
       public function registerMediaCollections(): void
      {
          $this->addMediaCollection('imagesProduct')->singleFile(); 
      }

}