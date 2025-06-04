<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            // Buyer Information
            $table->string('buyer_name');
            $table->string('buyer_surname');
            $table->date('buyer_birth_date');
            $table->string('buyer_address');
            $table->string('buyer_zip');
            $table->string('buyer_city');
            $table->string('buyer_phone');
            $table->string('buyer_email');
            
            // Vehicle Information
            $table->string('vehicle_brand');
            $table->string('vehicle_type');
            $table->date('first_registration_date');
            $table->integer('mileage');
            $table->string('chassis_number')->unique();
            $table->string('color');
            $table->string('plate_number');
            $table->boolean('has_accident')->default(false);
            
            // Sale Information
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->date('expertise_date')->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('remaining_amount', 10, 2)->nullable();
            $table->enum('payment_condition', ['cash', 'leasing', 'credit'])->nullable();
            $table->enum('warranty', ['no_warranty', 'quality_1_qbase', 'quality_1_q3', 'quality_1_q5'])->nullable();
            $table->decimal('warranty_amount', 10, 2)->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
