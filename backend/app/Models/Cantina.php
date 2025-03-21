<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Cantina extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'canteen_name',
        'corporate_reason',
        'cnpj',
        'telephone',
        'cell_phone',
        'state',
        'city',
        'neighborhood',
        'cep',
        'adress',
        'name_of_person_responsible',
        'email',
        'phone_of_responsible',
        'img',
        'open',
        'description',
        'opening_hours',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
     //Verifica se a cantina está aberta.

    public function isOpen()
    {
        return $this->open;
    }

    public function orders()
    {
    return $this->hasMany(Order::class);
    }

    public function annualManagements()
    {
    return $this->hasMany(MonthManagement::class);
    }
    public function dailyManagements()
    {
    return $this->hasMany(related: DailyManagement::class);
    }
    public function monthManagements()
    {
    return $this->hasMany(MonthManagement::class);
    }

    public function products()
    {
    return $this->hasMany(Product::class);
    }

    public function registerMediaConversionCanteen(Media $media = null): void
    {
        $this->addMediaConversion('thumbCanteen')
            ->width(100)
            ->height(100);
    }

     public function registerMediaCollectionsCanteen(): void
    {
        $this->addMediaCollection('imagesCanteen')->singleFile(); 
    }

}