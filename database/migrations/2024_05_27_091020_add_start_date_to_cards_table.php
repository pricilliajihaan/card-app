<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartDateToCardsTable extends Migration
{
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->date('start_date')->nullable(); // Tambahkan kolom start_date
            $table->integer('years_of_service')->nullable(); // Tambahkan kolom years_of_service
        });
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('years_of_service');
        });
    }
}

