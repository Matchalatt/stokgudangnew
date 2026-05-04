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
    Schema::table('transactions', function (Blueprint $table) {
        // Menambahkan kolom Asal (untuk In) / Tujuan (untuk Out)
        $table->string('asal_tujuan')->nullable()->after('qty_base');
        
        // Menambahkan kolom spesifik untuk Plat Nomor
        $table->string('plat_nomor')->nullable()->after('asal_tujuan');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
