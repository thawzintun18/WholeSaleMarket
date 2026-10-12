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
        Schema::create('crop_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('crop_id');

            // အရင် Data
            $table->string('old_crop_name');
            $table->integer('old_commission_amount');
            $table->string('old_unit');
            $table->integer('old_quantity_per_basket');

            // Data အသစ်
            $table->string('new_crop_name');
            $table->integer('new_commission_amount');
            $table->string('new_unit');
            $table->integer('new_quantity_per_basket');

            // ဘယ်အချိန် Update လုပ်ခဲ့သလဲ
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

            $table->index('crop_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_histories');
    }
};
