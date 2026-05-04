<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel users).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            
            $table->string('name'); // Nama pengguna (ditampilkan di dashboard nanti)
            
            $table->string('email')->unique(); // Email untuk login, harus unik tidak boleh ada yang sama
            
            $table->timestamp('email_verified_at')->nullable(); // Waktu verifikasi email (opsional, dibiarkan kosong tidak apa-apa)
            
            $table->string('password'); // Password yang sudah di-hash
            
            $table->rememberToken(); // Membuat kolom 'remember_token' VARCHAR(100) untuk fitur "Ingat saya"
            
            $table->timestamps(); // Membuat kolom 'created_at' dan 'updated_at'
        });
    }

    /**
     * Kembalikan migrasi (menghapus tabel users jika di-rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};