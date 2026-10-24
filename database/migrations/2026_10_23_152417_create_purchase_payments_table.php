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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->restrictOnDelete();
            $table->dateTime('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_type', 20);
            // initial = ဘောင်ချာထုတ်ချိန် ပေးချေငွေ
            // additional = ဘောင်ချာထုတ်ပြီးနောက် ထပ်မံပေးချေငွေ
            $table->timestamps();
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
