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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('name');
            $table->string('corporate_reason')->unique();//razao social
            $table->string('cnpj')->unique();
            $table->string('telephone');
            $table->string('cell_phone');
            $table->string('state');
            $table->string('city');
            $table->string('neighborhood');//bairro
            $table->string('cep');
            $table->string('adress');
            $table->string('name_of_person_responsible');
            $table->string('email')->unique()->email;
            $table->string('phone_of_responsible');
            $table->string('img');
            $table->boolean('open')->default(false);//se esta aberto ou fechado
            $table->string('description');
            $table->string('opening_hours');
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
