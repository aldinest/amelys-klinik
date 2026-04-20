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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('medical_record_number')
              ->nullable()
              ->unique();

            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone', 15);

            $table->enum('gender', ['L', 'P']);
            $table->date('date_of_birth')->nullable();

            // akun bisa nyusul
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
