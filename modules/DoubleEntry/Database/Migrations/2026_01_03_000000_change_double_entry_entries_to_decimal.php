<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDoubleEntryEntriesToDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('double_entry_entries', function (Blueprint $table) {
            $table->decimal('debit', 15, 4)->default(0)->change();
            $table->decimal('credit', 15, 4)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('double_entry_entries', function (Blueprint $table) {
            $table->double('debit', 15, 4)->default(0)->change();
            $table->double('credit', 15, 4)->default(0)->change();
        });
    }
}
