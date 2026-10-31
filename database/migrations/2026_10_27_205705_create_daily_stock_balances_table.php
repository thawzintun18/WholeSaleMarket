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
        Schema::create('daily_stock_balances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();

            $table->date('balance_date');

            $table->decimal('daily_balance', 15, 2)->default(0);
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('average_cost', 15, 2)
                ->default(0);

            $table->timestamps();

            $table->unique(['crop_id', 'balance_date']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_stock_balances');
    }
};
