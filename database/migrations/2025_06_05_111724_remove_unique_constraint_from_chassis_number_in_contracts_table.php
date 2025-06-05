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
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropUnique('contracts_chassis_number_unique');
            $table->string('chassis_number', 17)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Remove any duplicate chassis numbers before adding the unique constraint
            // This is important to prevent errors when rolling back
            \DB::statement('DELETE c1 FROM contracts c1 INNER JOIN contracts c2 WHERE c1.id > c2.id AND c1.chassis_number = c2.chassis_number');
            
            $table->unique('chassis_number');
        });
    }
};
