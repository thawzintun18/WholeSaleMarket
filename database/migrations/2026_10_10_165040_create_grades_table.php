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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')
                ->constrained('crops')
                ->restrictOnDelete();
            $table->string('grade_name', 50);
            $table->timestamps();
            // Soft Delete အတွက် deleted_at column
            $table->softDeletes();
            // သီးနှံတစ်မျိုးတည်းအောက်တွင် Grade အမည် မထပ်စေရန်
            $table->unique(['crop_id', 'grade_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
