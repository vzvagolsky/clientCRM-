<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->string('subject');
            $table->text('message');

            // проще всего string + default, или enum (ниже покажу вариант enum)
            $table->string('status', 32)->default('new');

            // Carbon-friendly
            $table->timestamp('answered_at')->nullable();

            $table->timestamps();

            // для статистики/фильтрации
            $table->index(['customer_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};