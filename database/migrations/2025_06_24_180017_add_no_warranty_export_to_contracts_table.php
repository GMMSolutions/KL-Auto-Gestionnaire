<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \DB::statement("ALTER TABLE contracts MODIFY COLUMN warranty ENUM('no_warranty', 'no_warranty_export', 'quality_1_qbase', 'quality_1_q3', 'quality_1_q5') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // First, update any records with the new enum value to an existing value
        \DB::table('contracts')
            ->where('warranty', 'no_warranty_export')
            ->update(['warranty' => 'no_warranty']);
            
        // Then modify the column to remove the enum value
        \DB::statement("ALTER TABLE contracts MODIFY COLUMN warranty ENUM('no_warranty', 'quality_1_qbase', 'quality_1_q3', 'quality_1_q5') NULL");
    }
};
