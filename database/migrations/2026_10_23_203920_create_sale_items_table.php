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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')
                ->constrained('sales')
                ->restrictOnDelete();

            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 3);
            $table->decimal('avg_price', 15, 2);
            $table->decimal('sale_price', 15, 2);
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
        Schema::dropIfExists('sale_items');
    }
};
