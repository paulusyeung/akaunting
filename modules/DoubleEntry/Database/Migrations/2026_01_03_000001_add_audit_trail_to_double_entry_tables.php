<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditTrailToDoubleEntryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('double_entry_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('enabled');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });

        Schema::table('double_entry_journals', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('double_entry_accounts', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('double_entry_journals', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
}
