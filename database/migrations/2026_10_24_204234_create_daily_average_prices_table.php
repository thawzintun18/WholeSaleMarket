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
        Schema::create('daily_average_prices', function (Blueprint $table) {
            $table->id();
            $table->date('price_date');
            $table->foreignId('crop_id')->constrained('crops')->restrictOnDelete();

            $table->decimal('total_gross_amount', 15, 2)->default(0);
            $table->decimal('quantity_on_hand', 15, 2)->default(0);

            // တစ်ပိဿာဈေး
            $table->decimal('average_cost', 15, 2)->default(0);

            // တစ်တင်းဈေး
            $table->decimal('average_basket_price', 15, 2)->default(0);

            $table->timestamps();

            $table->unique(['price_date', 'crop_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_average_prices');
    }
};
