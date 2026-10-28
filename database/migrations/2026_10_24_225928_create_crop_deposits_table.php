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
        Schema::create('crop_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_no', 40)->unique(); // အပ်နှံမှု ဘောင်ချာ နံပါတ် (ဥပမာ - DEP-20261024-001)
            $table->unsignedBigInteger('farmer_id');
            $table->dateTime('deposit_date');
            // Status: 'Stored' (သိမ်းဆည်းထားဆဲ), 'Converted' (ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းပြီး), 'Withdrawn' (ပြန်ထုတ်ယူသွားပြီ)
            $table->string('status', 20)->default('Stored');
            $table->string('car_no', 255)->nullable();
            $table->decimal('car_fee_amount', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('farmer_id');
            $table->index('deposit_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_deposits');
    }
};
