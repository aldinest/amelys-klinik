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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            
            // main relations
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->restrictOnDelete();
            // note: medical record = archive, patient must not be deleted

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->restrictOnDelete();

            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained('reservations')
                ->nullOnDelete();
            // note: reservation can be deleted, record stays

            // examination data
            $table->date('examined_at');
            $table->text('complaint');          // patient complaint
            $table->text('diagnosis');          // doctor diagnosis
            $table->text('treatment')->nullable();
            $table->text('doctor_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
