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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 40)->unique();
            $table->unsignedBigInteger('farmer_id');
            $table->dateTime('purchase_date');
            $table->string('status', 20)->default('Confirm'); // Confirm Temp Complete
            $table->decimal('total_gross_amount', 15, 2)->default(0);
            $table->decimal('advance_sub_amount', 15, 2)->default(0);
            $table->string('car_no', 255);
            $table->decimal('car_fee_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
