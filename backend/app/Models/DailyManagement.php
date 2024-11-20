<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyManagement extends Model
{
    use HasFactory;

    protected $table = 'daily_managements';

    protected $fillable = [
        'cantina_id',
        'total_sales_for_the_day',
        'day_profit',
        'average_value_of_day_sales',
        'month_reference',
        'day_best_seling_product',
    ];

    
    public function cantina()
    {
        return $this->belongsTo(Cantina::class);
    }

}
