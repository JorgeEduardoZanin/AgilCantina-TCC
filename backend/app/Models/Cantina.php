<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cantina extends Model
{
    use HasFactory;

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

    public function managements()
    {
    return $this->hasMany(Management::class);
    }

    public function products()
    {
    return $this->hasMany(Product::class);
    }

}