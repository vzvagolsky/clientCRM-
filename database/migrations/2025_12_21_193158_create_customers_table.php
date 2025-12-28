<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            // E.164: +491701234567 (до 15 цифр + "+") => до 16 символов
            $table->string('phone', 16);
            $table->string('email');

            // полезно для фильтрации и поиска
            $table->index('phone');
            $table->index('email');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};