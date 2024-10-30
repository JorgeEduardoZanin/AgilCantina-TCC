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
        Schema::create('cantinas', function (Blueprint $table) {
            $table->id();
            $table->string('corporate_reason'); // razão social
            $table->string('cnpj')->unique();
            $table->string('cell_phone');
            $table->string('state');
            $table->string('city');
            $table->string('neighborhood'); // bairro
            $table->string('cep');
            $table->string('name_of_person_responsible');
            $table->string('phone_of_responsible');
            $table->boolean('open')->default(false); // se está aberto ou fechado
            $table->string('description')->nullable();
            $table->string('opening_hours')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cantinas');
    }
};
