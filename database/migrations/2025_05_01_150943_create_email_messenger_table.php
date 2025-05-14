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
        Schema::create('email_messenger', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('email_id')->constrained()->onDelete('cascade');
            $table->foreignId('messenger_id')->constrained()->onDelete('cascade');

            $table->enum('relation_type', ['one_way', 'two_way']); // тип связи

            $table->unique(['user_id', 'email_id', 'messenger_id']); // защита от дубликатов

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messenger');
    }
};
