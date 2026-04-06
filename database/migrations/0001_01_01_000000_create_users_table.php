<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // NIK atau admin
            $table->string('password');
            $table->enum('role', ['admin', 'perangkat_desa', 'warga'])->default('warga');
            $table->foreignId('citizen_id')->nullable()->constrained('citizens')->nullOnDelete();
            $table->boolean('is_first_login')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};