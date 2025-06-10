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
        // First, modify the column to be nullable string to remove the enum constraint
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('payment_condition')->nullable()->change();
        });

        // Then update the column to be an enum with the new value
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('payment_condition', ['Cash', 'Leasing ou Crédit', 'Banque'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, modify the column to be nullable string to remove the enum constraint
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('payment_condition')->nullable()->change();
        });

        // Then update the column to be an enum with the original values
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('payment_condition', ['Cash', 'Leasing ou Crédit'])->nullable()->change();
        });
    }
};
