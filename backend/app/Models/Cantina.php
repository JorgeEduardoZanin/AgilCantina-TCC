<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cantina extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }


    
     //Verifica se a cantina está aberta.

    public function isOpen()
    {
        return $this->open;
    }

}