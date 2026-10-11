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
        Schema::create('grade_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->restrictOnDelete();

            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();

            $table->string('old_grade_name', 50);
            $table->string('new_grade_name', 50);

            // ဘယ်အချိန် Update လုပ်ခဲ့သလဲ
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

            $table->index('grade_id');
            $table->index('crop_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_histories');
    }
};
