<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualManagement extends Model
{
    use HasFactory;

    protected $table = 'annual_managements';

    protected $fillable = [
        'cantina_id',
        'total_sales_for_the_year',
        'annual_profit',
        'average_value_of_annual_sales',
        'month_reference',
        'annual_best_seling_product',
    ];

    
    public function cantina()
    {
        return $this->belongsTo(Cantina::class);
    }

}
