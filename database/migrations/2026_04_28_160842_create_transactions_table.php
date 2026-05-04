<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->onDelete('cascade');
        $table->enum('type', ['in', 'out']); // 'in' untuk masuk, 'out' untuk keluar
        $table->integer('qty_base'); // Jumlah total dalam satuan dasar (base_unit)
        $table->date('tanggal_fisik'); // Sesuai dengan Excel pelaporan Anda
        $table->string('reference')->nullable(); // Untuk Plat Nomor / No Surat Jalan
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
