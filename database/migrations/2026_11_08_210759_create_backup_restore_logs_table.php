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
        Schema::create('backup_restore_logs', function (Blueprint $table) {
            $table->id();

            // Restore လုပ်မည့် မူရင်း Backup
            $table->foreignId('source_backup_id')
                ->nullable()
                ->constrained('backup_logs')
                ->nullOnDelete();

            // Restore မလုပ်ခင် ယူထားသော Safety Backup
            $table->foreignId('safety_backup_id')
                ->nullable()
                ->constrained('backup_logs')
                ->nullOnDelete();

            $table->string('backup_path', 1000);

            // Pending, Success, Failed
            $table->string('status', 20)->default('Pending');

            $table->longText('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('started_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_restore_logs');
    }
};
