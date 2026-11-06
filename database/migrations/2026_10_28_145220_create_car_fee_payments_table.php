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
        Schema::create('car_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('car_no', 255);
            $table->string('driver_name', 150);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->date('payment_date');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_fee_payments');
    }
};
