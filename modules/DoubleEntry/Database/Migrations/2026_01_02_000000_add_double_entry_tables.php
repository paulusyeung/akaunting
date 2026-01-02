<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoubleEntryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('double_entry_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('double_entry_journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->date('date');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('double_entry_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('account_id');
            $table->double('debit', 15, 4)->default(0);
            $table->double('credit', 15, 4)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('journal_id');
            $table->index('account_id');
            
            $table->foreign('journal_id')->references('id')->on('double_entry_journals')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('double_entry_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('double_entry_entries');
        Schema::dropIfExists('double_entry_journals');
        Schema::dropIfExists('double_entry_accounts');
    }
}
