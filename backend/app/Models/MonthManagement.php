<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthManagement extends Model
{
    use HasFactory;

    protected $table = 'month_managements';

    protected $fillable = [
        'cantina_id',
        'total_monthly_sales',
        'monthly_profit',
        'average_value_of_monthly_sales',
        'month_reference',
        'best_seling_product',
    ];

    
    public function cantina()
    {
        return $this->belongsTo(Cantina::class);
    }

}
