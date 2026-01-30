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
         if (Schema::hasTable('invoice_items') && !Schema::hasColumn('invoice_items', 'sub_description')) {
            Schema::table('invoice_items', function (Blueprint $table) {
               $table->string('sub_description')->nullable()->after('description');
            });
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            //
        });
    }
};
