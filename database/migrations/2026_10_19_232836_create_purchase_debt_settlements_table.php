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
        Schema::create('purchase_debt_settlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmer_debt_id')
                ->constrained('farmer_debts')
                ->restrictOnDelete();

            $table->decimal('amount', 15, 2);

            $table->dateTime('settlement_date')->useCurrent();

            $table->timestamps();
            $table->softDeletes();

            $table->index('settlement_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_debt_settlements');
    }
};
