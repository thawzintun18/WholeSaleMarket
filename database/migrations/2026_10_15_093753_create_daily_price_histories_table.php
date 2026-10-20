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
        Schema::create('daily_price_histories', function (Blueprint $table) {
            $table->id();

            // မူရင်း Daily Price ID
            $table->foreignId('daily_price_id')
                ->constrained('daily_prices')
                ->restrictOnDelete();

            // သီးနှံအချက်အလက်

            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();

            // အရည်အသွေးအချက်အလက်

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->restrictOnDelete();

            // ဈေးနှုန်းသတ်မှတ်သည့်နေ့
            $table->date('price_date');

            // အရင်ဈေးနှုန်းနှင့် ပြင်ဆင်ပြီးဈေးနှုန်း
            $table->decimal('old_reference_buy_price', 15, 2)->nullable();
            $table->decimal('new_reference_buy_price', 15, 2)->nullable();

            // ဘယ်အချိန် Update လုပ်ခဲ့သလဲ
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

            // ရှာဖွေရာတွင် အဆင်ပြေရန် Index များ
            $table->index('daily_price_id');
            $table->index(['price_date', 'crop_id', 'grade_id']);
            $table->index('changed_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_price_histories');
    }
};
