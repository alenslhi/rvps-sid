<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('citizens')->cascadeOnDelete();
            $table->foreignId('letter_type_id')->constrained('letter_types')->restrictOnDelete();
            $table->enum('status', ['pending', 'process', 'finished', 'rejected'])->default('pending');
            $table->text('keperluan')->nullable();
            $table->json('data_tambahan')->nullable();
            $table->json('lampiran')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_requests');
    }
};