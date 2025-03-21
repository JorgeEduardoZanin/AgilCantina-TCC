<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annual_managements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('total_sales_for_the_year')->default(0);//total de vendas mensais
            $table->float('annual_profit')->default(0);//total de lucro mensal
            $table->float('average_value_of_annual_sales')->default(0);//media de vendas
            $table->date('month_reference');//mes de refenrencia
            $table->string('annual_best_seling_product');//produto mais vendido

            $table->foreignId('cantina_id')->constrained('cantinas')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_managements');
    }
};
