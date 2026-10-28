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
        Schema::create('crop_deposit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_deposit_id');
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('grade_id');
            $table->decimal('quantity', 15, 3);
            $table->integer('bag_count')->default(0); // unit က 'pound' ဖြစ်ပါက အိတ်အရေအတွက်
            $table->decimal('reference_price', 15, 2)->default(0);
            $table->decimal('actual_buy_price', 15, 2)->default(0);
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('crop_deposit_id');
            $table->index('crop_id');
            $table->index('grade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_deposit_items');
    }
};
