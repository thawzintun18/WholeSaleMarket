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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 40)->unique();
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->restrictOnDelete();

            $table->dateTime('sale_date');

            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('total_deduction', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
