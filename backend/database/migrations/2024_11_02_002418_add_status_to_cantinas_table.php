<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCantinasTable extends Migration
{
    public function up()
    {
        Schema::table('cantinas', function (Blueprint $table) {
            $table->string('status')->default('pending'); // "pending" ou "approved"
        });
    }

    public function down()
    {
        Schema::table('cantinas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
