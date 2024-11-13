<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    use HasFactory;

    protected $table = 'managements';

    protected $fillable = [
        'cantina_id',
        'total_monthly_sales',
        'monthly_profit',
        'daily_profit',
        'daily_sales',
        'total_sales',
        'total_profit',
        'total_monthly_expenses',
        'average_value_of_monthly_sales',
        'month_reference',
        'best_selling_product',
    ];

    
    public function cantina()
    {
        return $this->belongsTo(Cantina::class);
    }

}
