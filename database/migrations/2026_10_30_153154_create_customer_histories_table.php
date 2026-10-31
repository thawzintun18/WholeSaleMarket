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
        Schema::create('customer_histories', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 30)->nullable();

            $table->string('old_name', 150);
            $table->string('old_phone', 30)->nullable();
            $table->string('old_address', 150)->nullable();
            $table->text('old_notes')->nullable();

            $table->string('new_name', 150);
            $table->string('new_phone', 30)->nullable();
            $table->string('new_address', 150)->nullable();
            $table->text('new_notes')->nullable();

            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_histories');
    }
};
