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
        Schema::create('daily_prices', function (Blueprint $table) {
            $table->id();

            $table->date('price_date');

            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->restrictOnDelete();

            $table->decimal('reference_buy_price', 15, 2)
                ->nullable();

            // $table->decimal('reference_sell_price', 15, 2)->nullable();

            $table->timestamps();

            // Soft Delete
            $table->softDeletes();

            // တစ်နေ့တည်းမှာ သီးနှံနှင့် Grade တူညီသော ဈေးနှုန်း မထပ်စေရန်
            $table->unique(
                ['price_date', 'crop_id', 'grade_id'],
                'daily_prices_date_crop_grade_unique'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_prices');
    }
};
