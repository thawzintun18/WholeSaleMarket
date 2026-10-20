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
        Schema::create('farmer_debts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmer_id')
                ->constrained('farmers')
                ->restrictOnDelete();

            $table->string('debt_type', 30)->nullable(); // PurchaseAdvance CashLoan Complete
            $table->dateTime('debt_date')->useCurrent();

            $table->decimal('amount', 15, 2);
            $table->decimal('settled_amount', 15, 2)->nullable();
            $table->decimal('balance_amount', 15, 2)->nullable();

            $table->string('status', 20)->nullable(); // Open Close Partial

            $table->timestamps();
            $table->softDeletes();

            $table->index(['farmer_id', 'debt_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_debts');
    }
};
