<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'img',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    /**
     * Adicione métodos específicos para permissões ou operações de admin aqui.
     */
    public function canManageEverything()
    {
        return true; // Como admin, eles têm todas as permissões.
    }
}
