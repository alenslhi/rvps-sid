<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->enum('kategori', ['pendapatan', 'pengeluaran']);
            $table->string('label');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};