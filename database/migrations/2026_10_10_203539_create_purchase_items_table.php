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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('grade_id');
            $table->decimal('quantity', 15, 3);
            $table->decimal('reference_price', 15, 2)->default(0);
            $table->decimal('actual_buy_price', 15, 2);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('car_fee_amount', 15, 2)->default(0);
            $table->decimal('gross_amount', 15, 2);
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
